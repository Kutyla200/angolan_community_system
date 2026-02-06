<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Skill;
use App\Models\CommunitySupportArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getDashboardStats();
        $recentRegistrations = $this->getRecentRegistrations();
        $provinceDistribution = $this->getProvinceDistribution();
        $skillDistribution = $this->getSkillDistribution();
        
        return view('admin.dashboard', compact(
            'stats', 
            'recentRegistrations',
            'provinceDistribution',
            'skillDistribution'
        ));
    }

    public function members(Request $request)
    {
        $query = Member::query();
        
        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
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
        
        // Apply scope restrictions for coordinators
        if (auth('admin')->user()->isCoordinator()) {
            $query->where('province', auth('admin')->user()->assigned_province);
        }
        
        $members = $query->with('skills', 'supportAreas')
                        ->orderBy('created_at', 'desc')
                        ->paginate(20)
                        ->appends($request->all());
        
        $provinces = $this->getSouthAfricanProvinces();
        $skills = Skill::active()->ordered()->get();
        
        return view('admin.members.index', compact('members', 'provinces', 'skills'));
    }

    public function showMember($id)
    {
        $member = Member::with(['skills', 'supportAreas'])->findOrFail($id);
        
        // Check if coordinator can view this member
        if (auth('admin')->user()->isCoordinator() && 
            $member->province !== auth('admin')->user()->assigned_province) {
            abort(403);
        }
        
        return view('admin.members.show', compact('member'));
    }

    public function analytics()
    {
        $analyticsData = [
            'registration_trends' => $this->getRegistrationTrends(),
            'age_distribution' => $this->getAgeDistribution(),
            'employment_stats' => $this->getEmploymentStats(),
            'skill_demand' => $this->getSkillDemand(),
            'help_availability' => $this->getHelpAvailability(),
        ];
        
        return view('admin.analytics', compact('analyticsData'));
    }

    private function getDashboardStats()
    {
        $query = Member::query();
        
        if (auth('admin')->user()->isCoordinator()) {
            $query->where('province', auth('admin')->user()->assigned_province);
        }
        
        $total = $query->count();
        $today = $query->whereDate('created_at', today())->count();
        $thisWeek = $query->whereBetween('created_at', [now()->startOfWeek(), now()])->count();
        $willingToHelp = $query->where('willing_to_help', true)->count();
        
        return compact('total', 'today', 'thisWeek', 'willingToHelp');
    }

    private function getRecentRegistrations($limit = 10)
    {
        $query = Member::query();
        
        if (auth('admin')->user()->isCoordinator()) {
            $query->where('province', auth('admin')->user()->assigned_province);
        }
        
        return $query->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get(['id', 'first_name', 'last_name', 'province', 'city', 'created_at']);
    }

    private function getProvinceDistribution()
    {
        return Member::groupBy('province')
                    ->select('province', DB::raw('count(*) as count'))
                    ->orderBy('count', 'desc')
                    ->get();
    }

    private function getSkillDistribution()
    {
        return DB::table('member_skills')
                ->join('skills', 'member_skills.skill_id', '=', 'skills.id')
                ->select('skills.name_en', 'skills.name_pt', DB::raw('count(*) as count'))
                ->groupBy('skills.id', 'skills.name_en', 'skills.name_pt')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();
    }

    private function getRegistrationTrends($days = 30)
    {
        $startDate = now()->subDays($days);
        
        return Member::where('created_at', '>=', $startDate)
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
    }

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
                    ->orderByRaw('
                        CASE age_group
                            WHEN "Under 18" THEN 1
                            WHEN "18-25" THEN 2
                            WHEN "26-35" THEN 3
                            WHEN "36-50" THEN 4
                            ELSE 5
                        END
                    ')
                    ->get();
    }
}