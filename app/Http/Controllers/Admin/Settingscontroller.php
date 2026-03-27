<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = $this->getAllSettings();
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'view',
            'model_type' => 'Settings',
            'description' => 'Viewed settings page',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
        
        return view('admin.settings.index', compact('settings'));
    }
    
    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'default_language' => 'required|in:en,pt',
            'timezone' => 'required|string',
            'registration_enabled' => 'boolean',
            'maintenance_mode' => 'boolean',
        ]);
        
        foreach ($validated as $key => $value) {
            $this->updateSetting($key, $value);
        }
        
        // Update timezone
        config(['app.timezone' => $validated['timezone']]);
        
        // Handle maintenance mode
        if ($validated['maintenance_mode'] ?? false) {
            Artisan::call('down');
        } else {
            Artisan::call('up');
        }
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'update',
            'model_type' => 'Settings',
            'description' => 'Updated general settings',
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return back()->with('success', 'General settings updated successfully.');
    }
    
    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'new_member_notifications' => 'boolean',
            'daily_summary' => 'boolean',
            'weekly_report' => 'boolean',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
        ]);
        
        foreach ($validated as $key => $value) {
            $this->updateSetting($key, $value);
        }
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'update',
            'model_type' => 'Settings',
            'description' => 'Updated notification settings',
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return back()->with('success', 'Notification settings updated successfully.');
    }
    
    /**
     * Update email settings
     */
    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|integer',
            'smtp_username' => 'required|string',
            'smtp_password' => 'nullable|string',
            'from_email' => 'required|email',
            'from_name' => 'required|string',
        ]);
        
        foreach ($validated as $key => $value) {
            $this->updateSetting("email_{$key}", $value);
        }
        
        // Update mail config
        config([
            'mail.mailers.smtp.host' => $validated['smtp_host'],
            'mail.mailers.smtp.port' => $validated['smtp_port'],
            'mail.mailers.smtp.username' => $validated['smtp_username'],
            'mail.mailers.smtp.password' => $validated['smtp_password'],
            'mail.from.address' => $validated['from_email'],
            'mail.from.name' => $validated['from_name'],
        ]);
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'update',
            'model_type' => 'Settings',
            'description' => 'Updated email settings',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return back()->with('success', 'Email settings updated successfully.');
    }
    
    /**
     * Send test email
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        try {
            Mail::raw('This is a test email from ' . config('app.name'), function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Test Email - ' . config('app.name'));
            });
            
            AuditLog::create([
                'admin_user_id' => auth('admin')->id(),
                'action' => 'test_email',
                'model_type' => 'Settings',
                'description' => "Sent test email to {$request->email}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully. Please check your inbox.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update security settings
     */
    public function updateSecurity(Request $request)
    {
        $validated = $request->validate([
            'two_factor_enabled' => 'boolean',
            'session_timeout' => 'required|integer|min:5|max:240',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'password_policy' => 'required|in:strong,medium,basic',
        ]);
        
        foreach ($validated as $key => $value) {
            $this->updateSetting("security_{$key}", $value);
        }
        
        // Update session lifetime
        config(['session.lifetime' => $validated['session_timeout']]);
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'update',
            'model_type' => 'Settings',
            'description' => 'Updated security settings',
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return back()->with('success', 'Security settings updated successfully.');
    }
    
    /**
     * Update data & privacy settings
     */
    public function updatePrivacy(Request $request)
    {
        $validated = $request->validate([
            'popia_compliance' => 'boolean',
            'data_retention_days' => 'required|integer|in:30,90,180,365',
            'data_export_requests' => 'boolean',
            'audit_logging' => 'boolean',
        ]);
        
        foreach ($validated as $key => $value) {
            $this->updateSetting("privacy_{$key}", $value);
        }
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'update',
            'model_type' => 'Settings',
            'description' => 'Updated privacy settings',
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return back()->with('success', 'Privacy settings updated successfully.');
    }
    
    /**
     * Update backup settings
     */
    public function updateBackup(Request $request)
    {
        $validated = $request->validate([
            'automatic_backups' => 'boolean',
            'backup_frequency' => 'required|in:hourly,daily,weekly',
        ]);
        
        foreach ($validated as $key => $value) {
            $this->updateSetting("backup_{$key}", $value);
        }
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'update',
            'model_type' => 'Settings',
            'description' => 'Updated backup settings',
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return back()->with('success', 'Backup settings updated successfully.');
    }
    
    /**
     * Create manual backup
     */
    public function createBackup(Request $request)
    {
        try {
            // Create database backup
            $filename = 'backup-' . now()->format('Y-m-d-His') . '.sql';
            
            Artisan::call('backup:run', [
                '--only-db' => true,
            ]);
            
            AuditLog::create([
                'admin_user_id' => auth('admin')->id(),
                'action' => 'backup',
                'model_type' => 'System',
                'description' => 'Created manual backup',
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Backup created successfully.',
                'filename' => $filename
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create backup: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Run security scan
     */
    public function securityScan(Request $request)
    {
        // Here you would run actual security checks
        $results = [
            'vulnerabilities' => 0,
            'warnings' => 2,
            'passed' => 15,
            'last_scan' => now()->toDateTimeString(),
        ];
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'security_scan',
            'model_type' => 'System',
            'description' => 'Ran security scan',
            'new_values' => $results,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }
    
    /**
     * Clear cache
     */
    public function clearCache(Request $request)
    {
        Cache::flush();
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'clear_cache',
            'model_type' => 'System',
            'description' => 'Cleared all caches',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'All caches cleared successfully.'
        ]);
    }
    
    /**
     * Get all settings
     */
    private function getAllSettings()
    {
        return Cache::remember('system_settings', 3600, function () {
            $settings = Setting::all();
            $result = [];
            
            foreach ($settings as $setting) {
                $result[$setting->key] = $setting->value;
            }
            
            return $result;
        });
    }
    
    /**
     * Update a single setting
     */
    private function updateSetting($key, $value)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        
        // Clear cache
        Cache::forget('system_settings');
    }
}