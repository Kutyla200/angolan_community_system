<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Skill;
use App\Models\CommunitySupportArea;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        $stats = $this->getDashboardStats();
        $recentMembers = $this->getRecentMembers();
        $recentActivity = $this->getRecentActivity();
        $provinceStats = $this->getProvinceStats();
        $skillDistribution = $this->getSkillDistribution();
        
        return view('admin.dashboard', compact('stats', 'recentMembers', 'recentActivity', 'provinceStats', 'skillDistribution'));
    }
    
    /**
     * Display analytics page
     */
    public function analytics()
{
    // Get the base stats for the top cards
    $stats = $this->getDashboardStats();
    
    // Total for percentage calculations in the view
    $stats['total'] = Member::count();

    $analyticsData = [
        'registration_trends' => $this->getRegistrationTrends(),
        'age_distribution' => $this->getAgeDistribution(),
        'employment_stats' => $this->getEmploymentStats(),
        'skill_demand' => $this->getSkillDemand(),
        'help_availability' => $this->getHelpAvailability(),
        'gender_stats' => $this->getGenderStats(),
        'top_cities' => $this->getTopCities(),
        'province_growth' => $this->getProvinceGrowth(),
    ];

    // These two variables are explicitly called in analytics.blade.php
    $provinceDistribution = $this->getProvinceStats();
    $skillDistribution = $this->getSkillDistribution();

    return view('admin.analytics', compact('analyticsData', 'stats', 'provinceDistribution', 'skillDistribution'));
}
    /**
     * Get analytics data as JSON
     */
    public function getAnalyticsData()
    {
        return response()->json([
            'registration_trends' => $this->getRegistrationTrends(),
            'age_distribution' => $this->getAgeDistribution(),
            'employment_stats' => $this->getEmploymentStats(),
            'skill_demand' => $this->getSkillDemand(),
            'help_availability' => $this->getHelpAvailability(),
            'gender_stats' => $this->getGenderStats(),
            'top_cities' => $this->getTopCities(),
            
        ]);
    }
    private function getSkillDistribution()
{
    return DB::table('member_skills')
        ->join('skills', 'member_skills.skill_id', '=', 'skills.id')
        ->select(
            'skills.name_en',
            'skills.name_pt',
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('skills.id', 'skills.name_en', 'skills.name_pt')
        ->orderBy('count', 'desc')
        ->limit(10)
        ->get();
}

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
{
    $baseQuery = Member::query();

    if (auth('admin')->user()->isCoordinator()) {
        $baseQuery->where('province', auth('admin')->user()->assigned_province);
    }

    return [
        'total_members'   => (clone $baseQuery)->count(),
        'today'           => (clone $baseQuery)->whereDate('created_at', today())->count(),
        'this_week'       => (clone $baseQuery)->whereBetween('created_at', [now()->startOfWeek(), now()])->count(),
        'this_month'      => (clone $baseQuery)->whereBetween('created_at', [now()->startOfMonth(), now()])->count(),
        'willing_to_help' => (clone $baseQuery)->where('willing_to_help', true)->count(),
        'employed'        => (clone $baseQuery)->where('employment_status', 'employed')->count(),
        'students'        => (clone $baseQuery)->where('employment_status', 'student')->count(),
        'unemployed'      => (clone $baseQuery)->where('employment_status', 'unemployed')->count(),
        'growth_rate'     => $this->calculateGrowthRate(),
    ];
}

    
    /**
     * Get recent members
     */
    private function getRecentMembers($limit = 10)
    {
        $query = Member::with(['skills', 'supportAreas'])
            ->orderBy('created_at', 'desc')
            ->limit($limit);
        
        if (auth('admin')->user()->isCoordinator()) {
            $query->where('province', auth('admin')->user()->assigned_province);
        }
        
        return $query->get();
    }
    
    /**
     * Get recent activity from audit logs
     */
    private function getRecentActivity($limit = 15)
    {
        return AuditLog::with('adminUser')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get province statistics
     */
    private function getProvinceStats()
    {
        $query = Member::query();
        
        if (auth('admin')->user()->isCoordinator()) {
            $query->where('province', auth('admin')->user()->assigned_province);
        }
        
        return $query->groupBy('province')
            ->selectRaw('province, count(*) as count')
            ->orderBy('count', 'desc')
            ->get();
    }
    
    /**
     * Get registration trends (last 30 days)
     */
    private function getRegistrationTrends()
    {
        return Member::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => \Carbon\Carbon::parse($item->date)->format('M d'),
                    'count' => $item->count
                ];
            });
    }
    
    /**
     * Get age distribution
     */
    private function getAgeDistribution()
    {
        return Member::whereNotNull('date_of_birth')
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
            ->orderByRaw('MIN(TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()))')
            ->get();
    }
    
    /**
     * Get employment statistics
     */
    private function getEmploymentStats()
    {
        return Member::groupBy('employment_status')
            ->selectRaw('employment_status as status, count(*) as count')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => ucfirst(str_replace('_', ' ', $item->status)),
                    'count' => $item->count
                ];
            });
    }
    
    /**
     * Get skill demand (top 10 skills)
     */
    private function getSkillDemand()
    {
        return DB::table('member_skills')
            ->join('skills', 'member_skills.skill_id', '=', 'skills.id')
            ->select('skills.name_en', 'skills.name_pt', DB::raw('count(*) as count'))
            ->groupBy('skills.id', 'skills.name_en', 'skills.name_pt')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
    }
    
    /**
     * Get help availability
     */
    private function getHelpAvailability()
    {
        $willing = Member::where('willing_to_help', true)->count();
        $total = Member::count();
        
        return [
            'willing' => $willing,
            'not_willing' => $total - $willing,
            'percentage' => $total > 0 ? round(($willing / $total) * 100, 2) : 0
        ];
    }
    
    /**
     * Get gender statistics
     */
    private function getGenderStats()
    {
        return Member::groupBy('gender')
            ->selectRaw('gender, count(*) as count')
            ->get()
            ->mapWithKeys(function ($item) {
                return [ucfirst($item->gender) => $item->count];
            })
            ->toArray();
    }
    
    /**
     * Get top cities by member count
     */
    private function getTopCities()
    {
        return Member::groupBy('city', 'province')
            ->selectRaw('city, province, count(*) as count')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
    }
    
    /**
     * Get province growth over time
     */
    private function getProvinceGrowth()
    {
        return Member::selectRaw('
                province,
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as count
            ')
            ->whereBetween('created_at', [now()->subMonths(6), now()])
            ->groupBy('province', 'month')
            ->orderBy('month')
            ->get()
            ->groupBy('province');
    }
    
    /**
     * Calculate growth rate compared to last month
     */
    private function calculateGrowthRate()
    {
        $thisMonth = Member::whereBetween('created_at', [now()->startOfMonth(), now()])->count();
        $lastMonth = Member::whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        ])->count();
        
        if ($lastMonth == 0) {
            return $thisMonth > 0 ? 100 : 0;
        }
        
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 2);
    }
}