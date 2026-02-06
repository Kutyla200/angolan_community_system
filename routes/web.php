<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\Auth\LoginController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Registration Routes
Route::get('/register', [RegistrationController::class, 'index'])->name('registration');
Route::post('/register', [RegistrationController::class, 'store'])->name('registration.store');

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Members
    Route::get('/members', [DashboardController::class, 'members'])->name('members');
    Route::get('/members/{id}', [DashboardController::class, 'showMember'])->name('members.show');
    Route::delete('/members/{id}', [DashboardController::class, 'destroy'])->name('members.destroy');
    
    // Analytics
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    
    // Export
    Route::get('/export/csv', [ExportController::class, 'csv'])->name('export.csv');
    Route::get('/export/pdf', [ExportController::class, 'pdf'])->name('export.pdf');
    Route::get('/export/skills', [ExportController::class, 'skillsReport'])->name('export.skills');
    Route::get('/export/analytics', [ExportController::class, 'analyticsReport'])->name('export.analytics');
});

// API Routes for AJAX requests
Route::prefix('api')->group(function () {
    Route::get('/members/stats', [DashboardController::class, 'getStats']);
    Route::get('/analytics/data', [DashboardController::class, 'getAnalyticsData']);
});