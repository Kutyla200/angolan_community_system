<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AuditLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes (redirect to admin)
Route::get('/', function () {
    return redirect()->route('admin.login');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Guest routes (not authenticated)
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->name('login.post');
    });
    
    // Logout route (authenticated)
    Route::post('logout', [LoginController::class, 'logout'])
        ->name('logout')
        ->middleware('auth:admin');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    
    // ==================== DASHBOARD ====================
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('analytics/data', [DashboardController::class, 'getAnalyticsData'])->name('analytics.data');
    
    // ==================== MEMBERS MANAGEMENT ====================
    Route::prefix('members')->name('members.')->group(function () {
        // List and view
        Route::get('/', [MemberController::class, 'index'])->name('index');
        Route::get('/{id}', [MemberController::class, 'show'])->name('show')->where('id', '[0-9]+');
        Route::get('/{id}/edit', [MemberController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
        
        // Update and delete
        Route::put('/{id}', [MemberController::class, 'update'])->name('update')->where('id', '[0-9]+');
        Route::delete('/{id}', [MemberController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
        
        // Bulk operations
        Route::post('bulk-delete', [MemberController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('send-message', [MemberController::class, 'sendMessage'])->name('send-message');
        Route::post('import', [MemberController::class, 'import'])->name('import');
        
        // AJAX/API routes
        Route::get('api/stats', [MemberController::class, 'getStats'])->name('api.stats');
    });
    
    // ==================== EXPORT ROUTES ====================
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('csv', [ExportController::class, 'csv'])->name('csv');
        Route::get('excel', [ExportController::class, 'excel'])->name('excel');
        Route::get('pdf', [ExportController::class, 'pdf'])->name('pdf');
        Route::get('skills', [ExportController::class, 'skillsReport'])->name('skills');
        Route::get('analytics', [ExportController::class, 'analyticsReport'])->name('analytics');
    });
    
    // ==================== SETTINGS ROUTES ====================
    Route::prefix('settings')->name('settings.')->group(function () {
        // Main settings page
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        
        // Update different setting groups
        Route::post('general', [SettingsController::class, 'updateGeneral'])->name('update.general');
        Route::post('notifications', [SettingsController::class, 'updateNotifications'])->name('update.notifications');
        Route::post('email', [SettingsController::class, 'updateEmail'])->name('update.email');
        Route::post('security', [SettingsController::class, 'updateSecurity'])->name('update.security');
        Route::post('privacy', [SettingsController::class, 'updatePrivacy'])->name('update.privacy');
        Route::post('backup', [SettingsController::class, 'updateBackup'])->name('update.backup');
        
        // Actions
        Route::post('test-email', [SettingsController::class, 'sendTestEmail'])->name('test-email');
        Route::post('create-backup', [SettingsController::class, 'createBackup'])->name('create-backup');
        Route::post('security-scan', [SettingsController::class, 'securityScan'])->name('security-scan');
        Route::post('clear-cache', [SettingsController::class, 'clearCache'])->name('clear-cache');
    });
    
    // ==================== AUDIT LOGS ROUTES ====================
    Route::prefix('logs')->name('logs.')->group(function () {
        // Main logs page
        Route::get('/', [AuditLogController::class, 'index'])->name('index');
        Route::get('/{id}', [AuditLogController::class, 'show'])->name('show')->where('id', '[0-9]+');
        
        // Export logs
        Route::get('export', [AuditLogController::class, 'export'])->name('export');
        
        // AJAX routes
        Route::get('api/statistics', [AuditLogController::class, 'getStatistics'])->name('api.statistics');
        Route::get('api/security-alerts', [AuditLogController::class, 'getSecurityAlerts'])->name('api.security-alerts');
        Route::post('block-ip', [AuditLogController::class, 'blockIp'])->name('block-ip');
    });
});