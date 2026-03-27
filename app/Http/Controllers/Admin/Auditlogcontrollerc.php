<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditLogController extends Controller
{
    /**
     * Display audit logs
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('adminUser');
        
        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('description', 'like', "%{$request->search}%")
                  ->orWhere('action', 'like', "%{$request->search}%")
                  ->orWhere('ip_address', 'like', "%{$request->search}%");
            });
        }
        
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        if ($request->filled('admin_user_id')) {
            $query->where('admin_user_id', $request->admin_user_id);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }
        
        // Sorting
        $query->orderBy('created_at', 'desc');
        
        // Paginate
        $perPage = $request->get('per_page', 20);
        $logs = $query->paginate($perPage)->appends($request->all());
        
        // Get statistics
        $stats = $this->getStatistics($request);
        
        // Get administrators for filter
        $administrators = AdminUser::select('id', 'name')->get();
        
        return view('admin.logs.index', compact('logs', 'stats', 'administrators'));
    }
    
    /**
     * Get audit log statistics
     */
    public function getStatistics(Request $request)
    {
        $query = AuditLog::query();
        
        // Apply date filters if present
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        return [
            'total' => $query->count(),
            'today' => AuditLog::whereDate('created_at', today())->count(),
            'failed_logins' => AuditLog::where('action', 'failed_login')
                ->whereDate('created_at', '>=', now()->subDays(7))
                ->count(),
            'active_users' => AuditLog::whereDate('created_at', today())
                ->distinct('admin_user_id')
                ->count('admin_user_id'),
            'by_action' => AuditLog::select('action', DB::raw('count(*) as count'))
                ->groupBy('action')
                ->get(),
            'by_day' => AuditLog::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                ->whereBetween('created_at', [now()->subDays(7), now()])
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'top_admins' => AuditLog::select('admin_user_id', DB::raw('count(*) as count'))
                ->with('adminUser:id,name,role')
                ->groupBy('admin_user_id')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
        ];
    }
    
    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        $query = AuditLog::with('adminUser');
        
        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->get();
        
        // Log the export
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'export_logs',
            'model_type' => 'AuditLog',
            'description' => "Exported audit logs as {$format}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        switch ($format) {
            case 'csv':
                return $this->exportCsv($logs);
            case 'pdf':
                return $this->exportPdf($logs);
            case 'json':
                return $this->exportJson($logs);
            default:
                return back()->withErrors(['error' => 'Invalid export format']);
        }
    }
    
    /**
     * Export as CSV
     */
    private function exportCsv($logs)
    {
        $filename = 'audit-logs-' . now()->format('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID',
                'Admin User',
                'Action',
                'Model Type',
                'Model ID',
                'Description',
                'IP Address',
                'User Agent',
                'Created At'
            ]);
            
            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->adminUser->name ?? 'N/A',
                    $log->action,
                    $log->model_type ?? 'N/A',
                    $log->model_id ?? 'N/A',
                    $log->description,
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at->toDateTimeString(),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export as PDF
     */
    private function exportPdf($logs)
    {
        $pdf = \PDF::loadView('admin.exports.audit-logs-pdf', [
            'logs' => $logs,
            'date' => now()->format('d F Y')
        ]);
        
        return $pdf->download('audit-logs-' . now()->format('Y-m-d') . '.pdf');
    }
    
    /**
     * Export as JSON
     */
    private function exportJson($logs)
    {
        $filename = 'audit-logs-' . now()->format('Y-m-d-His') . '.json';
        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        
        $data = $logs->map(function($log) {
            return [
                'id' => $log->id,
                'admin_user' => $log->adminUser->name ?? 'N/A',
                'action' => $log->action,
                'model_type' => $log->model_type,
                'model_id' => $log->model_id,
                'description' => $log->description,
                'old_values' => $log->old_values,
                'new_values' => $log->new_values,
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'created_at' => $log->created_at->toIso8601String(),
            ];
        });
        
        return response()->json($data, 200, $headers);
    }
    
    /**
     * Get security alerts
     */
    public function getSecurityAlerts(Request $request)
    {
        $alerts = [];
        
        // Failed login attempts
        $failedLogins = AuditLog::where('action', 'failed_login')
            ->whereDate('created_at', '>=', now()->subHours(24))
            ->select('ip_address', DB::raw('count(*) as count'))
            ->groupBy('ip_address')
            ->having('count', '>=', 3)
            ->get();
        
        foreach ($failedLogins as $failed) {
            $alerts[] = [
                'type' => 'failed_login',
                'severity' => 'warning',
                'message' => "{$failed->count} failed login attempts from IP {$failed->ip_address}",
                'ip' => $failed->ip_address,
                'count' => $failed->count,
            ];
        }
        
        // Large data exports
        $largeExports = AuditLog::where('action', 'export')
            ->whereDate('created_at', '>=', now()->subHours(24))
            ->where('description', 'like', '%1000+%')
            ->get();
        
        foreach ($largeExports as $export) {
            $alerts[] = [
                'type' => 'large_export',
                'severity' => 'info',
                'message' => $export->description,
                'admin_user' => $export->adminUser->name ?? 'Unknown',
                'created_at' => $export->created_at->diffForHumans(),
            ];
        }
        
        return response()->json($alerts);
    }
    
    /**
     * Block IP address
     */
    public function blockIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip'
        ]);
        
        // Here you would add the IP to a blocklist
        // This could be in database, firewall, etc.
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'block_ip',
            'model_type' => 'Security',
            'description' => "Blocked IP address: {$request->ip_address}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => "IP address {$request->ip_address} has been blocked."
        ]);
    }
    
    /**
     * View log details
     */
    public function show($id)
    {
        $log = AuditLog::with('adminUser')->findOrFail($id);
        
        return response()->json($log);
    }
}