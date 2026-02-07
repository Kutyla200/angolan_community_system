<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Skill;
use App\Models\CommunitySupportArea;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MembersExport;
use App\Exports\SkillsExport;

class ExportController extends Controller
{
    /**
     * Export members as CSV
     */
    public function csv(Request $request)
    {
        $query = $this->getMembersQuery($request);
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'export_csv',
            'model_type' => 'Member',
            'description' => 'Exported members data to CSV',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return Excel::download(
            new MembersExport($query), 
            'angolan-community-members-' . date('Y-m-d') . '.csv',
            \Maatwebsite\Excel\Excel::CSV
        );
    }
    
    /**
     * Export members as Excel
     */
    public function excel(Request $request)
    {
        $query = $this->getMembersQuery($request);
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'export_excel',
            'model_type' => 'Member',
            'description' => 'Exported members data to Excel',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return Excel::download(
            new MembersExport($query), 
            'angolan-community-members-' . date('Y-m-d') . '.xlsx'
        );
    }
    
    /**
     * Export members as PDF
     */
    public function pdf(Request $request)
    {
        $query = $this->getMembersQuery($request);
        
        $members = $query->with(['skills', 'supportAreas'])
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();
        
        $stats = [
            'total' => $members->count(),
            'provinces' => $members->groupBy('province')->count(),
            'willing_to_help' => $members->where('willing_to_help', true)->count(),
        ];
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'export_pdf',
            'model_type' => 'Member',
            'description' => 'Exported members data to PDF',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        $pdf = \PDF::loadView('admin.exports.members-pdf', [
            'members' => $members,
            'stats' => $stats,
            'date' => now()->format('d F Y')
        ])->setPaper('a4', 'landscape');
        
        return $pdf->download('angolan-community-report-' . date('Y-m-d') . '.pdf');
    }
    
    /**
     * Export skills report
     */
    public function skillsReport(Request $request)
    {
        $skills = Skill::withCount('members')->get();
        $supportAreas = CommunitySupportArea::withCount('members')->get();
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'export_skills',
            'model_type' => 'Skill',
            'description' => 'Exported skills inventory report',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        $pdf = \PDF::loadView('admin.exports.skills-pdf', [
            'skills' => $skills,
            'supportAreas' => $supportAreas,
            'date' => now()->format('d F Y')
        ]);
        
        return $pdf->download('skills-inventory-' . date('Y-m-d') . '.pdf');
    }
    
    /**
     * Export analytics report
     */
    public function analyticsReport(Request $request)
    {
        $stats = $this->getAnalyticsData();
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'export_analytics',
            'model_type' => 'Analytics',
            'description' => 'Exported analytics report',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        $pdf = \PDF::loadView('admin.exports.analytics-pdf', [
            'stats' => $stats,
            'date' => now()->format('d F Y')
        ]);
        
        return $pdf->download('community-analytics-' . date('Y-m-d') . '.pdf');
    }
    
    /**
     * Get members query with filters and role restrictions
     */
    private function getMembersQuery(Request $request)
    {
        $query = Member::query();
        
        // Apply role-based restrictions
        if (auth('admin')->user()->isCoordinator()) {
            $query->where('province', auth('admin')->user()->assigned_province);
        }
        
        // Apply filters if provided
        if ($request->filled('ids')) {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $query->whereIn('id', $ids);
        }
        
        if ($request->filled('province')) {
            $query->where('province', $request->province);
        }
        
        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }
        
        if ($request->filled('employment_status')) {
            $query->where('employment_status', $request->employment_status);
        }
        
        if ($request->filled('willing_to_help')) {
            $query->where('willing_to_help', $request->willing_to_help === 'yes');
        }
        
        if ($request->filled('skill')) {
            $query->whereHas('skills', function ($q) use ($request) {
                $q->where('skills.id', $request->skill);
            });
        }
        
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }
        
        return $query;
    }
    
    /**
     * Get analytics data
     */
    private function getAnalyticsData()
    {
        return [
            'total_members' => Member::count(),
            'today_registrations' => Member::whereDate('created_at', today())->count(),
            'weekly_registrations' => Member::whereBetween('created_at', [now()->startOfWeek(), now()])->count(),
            'monthly_registrations' => Member::whereBetween('created_at', [now()->startOfMonth(), now()])->count(),
            'province_distribution' => Member::groupBy('province')
                ->selectRaw('province, count(*) as count')
                ->orderBy('count', 'desc')
                ->get(),
            'employment_stats' => Member::groupBy('employment_status')
                ->selectRaw('employment_status as status, count(*) as count')
                ->get(),
            'willing_to_help' => Member::where('willing_to_help', true)->count(),
            'top_skills' => \DB::table('member_skills')
                ->join('skills', 'member_skills.skill_id', '=', 'skills.id')
                ->select('skills.name_en', 'skills.name_pt', \DB::raw('count(*) as count'))
                ->groupBy('skills.id', 'skills.name_en', 'skills.name_pt')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'age_distribution' => Member::whereNotNull('date_of_birth')
                ->selectRaw('
                    CASE
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 18 THEN "Under 18"
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 25 THEN "18-25"
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 26 AND 35 THEN "26-35"
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 36 AND 50 THEN "36-50"
                        ELSE "51+"
                    END as age_group,
                    COUNT(*) as count
                ')
                ->groupBy('age_group')
                ->get(),
            'gender_stats' => Member::groupBy('gender')
                ->selectRaw('gender, count(*) as count')
                ->get()
                ->pluck('count', 'gender')
                ->toArray(),
            'registration_trends' => Member::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->whereBetween('created_at', [now()->subDays(30), now()])
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'top_cities' => Member::groupBy('city', 'province')
                ->selectRaw('city, province, count(*) as count')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
        ];
    }
}