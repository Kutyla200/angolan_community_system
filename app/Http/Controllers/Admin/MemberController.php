<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Skill;
use App\Models\CommunitySupportArea;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    /**
     * Display a listing of members with advanced filtering
     */
    public function index(Request $request)
    {
        $query = Member::query();
        
        // Apply role-based scope
        if (auth('admin')->user()->isCoordinator()) {
            $query->where('province', auth('admin')->user()->assigned_province);
        }
        
        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('mobile_number', 'like', "%{$request->search}%")
                  ->orWhere('registration_number', 'like', "%{$request->search}%");
            });
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
        
        // Eager load relationships
        $query->with(['skills', 'supportAreas']);
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        // Paginate
        $perPage = $request->get('per_page', 20);
        $members = $query->paginate($perPage)->appends($request->all());
        
        // Get filter options
        $provinces = $this->getSouthAfricanProvinces();
        $skills = Skill::active()->ordered()->get();
        
        // Log activity
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'view',
            'model_type' => 'Member',
            'description' => 'Viewed members list',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return view('admin.members.index', compact('members', 'provinces', 'skills'));
    }
    
    /**
     * Display the specified member
     */
    public function show($id)
    {
        $member = Member::with(['skills', 'supportAreas'])->findOrFail($id);
        
        // Check permissions
        if (auth('admin')->user()->isCoordinator() && 
            $member->province !== auth('admin')->user()->assigned_province) {
            abort(403, 'You do not have permission to view this member.');
        }
        
        // Log activity
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'view',
            'model_type' => 'Member',
            'model_id' => $member->id,
            'description' => "Viewed member: {$member->full_name}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
        
        return view('admin.members.show', compact('member'));
    }
    
    /**
     * Show the form for editing the specified member
     */
    public function edit($id)
    {
        $member = Member::with(['skills', 'supportAreas'])->findOrFail($id);
        
        // Check permissions
        if (auth('admin')->user()->isCoordinator() && 
            $member->province !== auth('admin')->user()->assigned_province) {
            abort(403, 'You do not have permission to edit this member.');
        }
        
        $skills = Skill::active()->ordered()->get();
        $supportAreas = CommunitySupportArea::active()->ordered()->get();
        $provinces = $this->getSouthAfricanProvinces();
        $genders = $this->getGenderOptions();
        
        return view('admin.members.edit', compact('member', 'skills', 'supportAreas', 'provinces', 'genders'));
    }
    
    /**
     * Update the specified member in storage
     */
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        
        // Check permissions
        if (auth('admin')->user()->isCoordinator() && 
            $member->province !== auth('admin')->user()->assigned_province) {
            abort(403);
        }
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|in:male,female,other,prefer_not_to_say',
            'date_of_birth' => 'nullable|date|before:today',
            'nationality' => 'required|string|max:100',
            'citizenship_status' => 'required|in:angolan,south_african,dual_citizenship,other',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'area' => 'nullable|string|max:100',
            'mobile_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'whatsapp_number' => 'nullable|string|max:20',
            'preferred_contact_method' => 'required|in:phone,whatsapp,email',
            'employment_status' => 'required|in:employed,self_employed,student,unemployed,retired',
            'profession' => 'nullable|string|max:100',
            'field_of_study' => 'nullable|string|max:100',
            'willing_to_help' => 'boolean',
        ]);
        
        DB::beginTransaction();
        try {
            // Store old values for audit
            $oldValues = $member->toArray();
            
            // Update member
            $member->update($validated);
            
            // Update skills if provided
            if ($request->has('skills')) {
                $skillsData = [];
                foreach ($request->skills as $skillId => $data) {
                    $skillsData[$skillId] = [
                        'experience_level' => $data['level'] ?? 'intermediate',
                        'years_experience' => $data['years'] ?? null,
                        'description' => $data['description'] ?? null
                    ];
                }
                $member->skills()->sync($skillsData);
            }
            
            // Update support areas if provided
            if ($request->has('support_areas')) {
                $member->supportAreas()->sync($request->support_areas);
            }
            
            // Log activity
            AuditLog::create([
                'admin_user_id' => auth('admin')->id(),
                'action' => 'update',
                'model_type' => 'Member',
                'model_id' => $member->id,
                'old_values' => $oldValues,
                'new_values' => $member->fresh()->toArray(),
                'description' => "Updated member: {$member->full_name}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.members.show', $member->id)
                ->with('success', 'Member updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update member: ' . $e->getMessage()])
                ->withInput();
        }
    }
    
    /**
     * Remove the specified member from storage
     */
    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        
        // Check permissions
        if (auth('admin')->user()->isCoordinator() && 
            $member->province !== auth('admin')->user()->assigned_province) {
            abort(403);
        }
        
        // Store member data for audit
        $memberData = $member->toArray();
        
        // Soft delete member
        $member->delete();
        
        // Log activity
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'delete',
            'model_type' => 'Member',
            'model_id' => $id,
            'old_values' => $memberData,
            'description' => "Deleted member: {$member->full_name}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Member deleted successfully.'
        ]);
    }
    
    /**
     * Bulk delete members
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:members,id'
        ]);
        
        $query = Member::whereIn('id', $request->ids);
        
        // Apply role-based restrictions
        if (auth('admin')->user()->isCoordinator()) {
            $query->where('province', auth('admin')->user()->assigned_province);
        }
        
        $count = $query->count();
        $query->delete();
        
        // Log activity
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'bulk_delete',
            'model_type' => 'Member',
            'description' => "Bulk deleted {$count} members",
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => "{$count} members deleted successfully."
        ]);
    }
    
    /**
     * Send message to member(s)
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'member_ids' => 'required|array',
            'member_ids.*' => 'exists:members,id',
            'message' => 'required|string',
            'type' => 'required|in:email,sms,whatsapp'
        ]);
        
        $members = Member::whereIn('id', $request->member_ids)->get();
        
        foreach ($members as $member) {
            // Here you would integrate with your messaging service
            // For now, we'll just log it
            \Log::info("Message sent to {$member->full_name}: {$request->message}");
        }
        
        // Log activity
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'send_message',
            'model_type' => 'Member',
            'description' => "Sent {$request->type} message to " . count($request->member_ids) . " members",
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.'
        ]);
    }
    
    /**
     * Import members from file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240'
        ]);
        
        // Here you would process the import
        // Using Laravel Excel or similar
        
        AuditLog::create([
            'admin_user_id' => auth('admin')->id(),
            'action' => 'import',
            'model_type' => 'Member',
            'description' => 'Imported members from file',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Members imported successfully.'
        ]);
    }
    
    /**
     * Get statistics for dashboard
     */
    public function getStats(Request $request)
    {
        $query = Member::query();
        
        if (auth('admin')->user()->isCoordinator()) {
            $query->where('province', auth('admin')->user()->assigned_province);
        }
        
        $stats = [
            'total' => $query->count(),
            'today' => $query->whereDate('created_at', today())->count(),
            'this_week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()])->count(),
            'this_month' => $query->whereBetween('created_at', [now()->startOfMonth(), now()])->count(),
            'willing_to_help' => $query->where('willing_to_help', true)->count(),
            'by_province' => Member::groupBy('province')
                ->selectRaw('province, count(*) as count')
                ->orderBy('count', 'desc')
                ->get(),
            'by_employment' => Member::groupBy('employment_status')
                ->selectRaw('employment_status, count(*) as count')
                ->get(),
        ];
        
        return response()->json($stats);
    }
    
    /**
     * Helper: Get South African provinces
     */
    private function getSouthAfricanProvinces()
    {
        return [
            'Eastern Cape' => __('Eastern Cape'),
            'Free State' => __('Free State'),
            'Gauteng' => __('Gauteng'),
            'KwaZulu-Natal' => __('KwaZulu-Natal'),
            'Limpopo' => __('Limpopo'),
            'Mpumalanga' => __('Mpumalanga'),
            'North West' => __('North West'),
            'Northern Cape' => __('Northern Cape'),
            'Western Cape' => __('Western Cape'),
        ];
    }
    
    /**
     * Helper: Get gender options
     */
    private function getGenderOptions()
    {
        return [
            'male' => __('Male'),
            'female' => __('Female'),
            'other' => __('Other'),
            'prefer_not_to_say' => __('Prefer not to say')
        ];
    }
}