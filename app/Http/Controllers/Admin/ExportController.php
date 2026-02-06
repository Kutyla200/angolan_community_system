<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Skill;
use App\Models\CommunitySupportArea;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MembersExport;
use App\Exports\SkillsExport;
use App\Exports\AnalyticsExport;
use PDF;

class ExportController extends Controller
{
    public function csv(Request $request)
    {
        // Log export action
        \App\Models\AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'export_csv',
            'description' => 'Exported members data to CSV',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return Excel::download(new MembersExport, 'angolan-community-members-' . date('Y-m-d') . '.csv');
    }
    
    public function pdf(Request $request)
    {
        $query = Member::query();
        
        if (auth('admin')->user()->isCoordinator()) {
            $query->where('province', auth('admin')->user()->assigned_province);
        }
        
        $members = $query->with(['skills', 'supportAreas'])
                        ->orderBy('created_at', 'desc')
                        ->limit(100)
                        ->get();
        
        $stats = [
            'total' => $members->count(),
            'provinces' => $members->groupBy('province')->count(),
            'willing_to_help' => $members->where('willing_to_help', true)->count(),
        ];
        
        // Log export action
        \App\Models\AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'export_pdf',
            'description' => 'Exported members data to PDF',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        $pdf = PDF::loadView('admin.exports.members-pdf', [
            'members' => $members,
            'stats' => $stats,
            'date' => now()->format('d F Y')
        ]);
        
        return $pdf->download('angolan-community-report-' . date('Y-m-d') . '.pdf');
    }
    
    public function skillsReport(Request $request)
    {
        $skills = Skill::withCount('members')->get();
        $supportAreas = CommunitySupportArea::withCount('members')->get();
        
        $pdf = PDF::loadView('admin.exports.skills-pdf', [
            'skills' => $skills,
            'supportAreas' => $supportAreas,
            'date' => now()->format('d F Y')
        ]);
        
        return $pdf->download('skills-inventory-' . date('Y-m-d') . '.pdf');
    }
    
    public function analyticsReport(Request $request)
    {
        $stats = $this->getAnalyticsData();
        
        $pdf = PDF::loadView('admin.exports.analytics-pdf', [
            'stats' => $stats,
            'date' => now()->format('d F Y')
        ]);
        
        return $pdf->download('community-analytics-' . date('Y-m-d') . '.pdf');
    }
    
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
        ];
    }
}