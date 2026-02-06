<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Skill;
use App\Models\CommunitySupportArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function index()
    {
        $skills = Skill::active()->ordered()->get();
        $supportAreas = CommunitySupportArea::active()->ordered()->get();
        
        return view('registration.index', [
            'skills' => $skills,
            'supportAreas' => $supportAreas,
            'provinces' => $this->getSouthAfricanProvinces(),
            'genders' => [
                'male' => __('Male'),
                'female' => __('Female'),
                'other' => __('Other'),
                'prefer_not_to_say' => __('Prefer not to say')
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = $this->validateRegistration($request);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Create member
            $member = Member::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'nationality' => $request->nationality,
                'citizenship_status' => $request->citizenship_status,
                'other_citizenship' => $request->other_citizenship,
                'province' => $request->province,
                'city' => $request->city,
                'area' => $request->area,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'preferred_contact_method' => $request->preferred_contact_method,
                'whatsapp_number' => $request->whatsapp_number,
                'employment_status' => $request->employment_status,
                'profession' => $request->profession,
                'field_of_study' => $request->field_of_study,
                'willing_to_help' => $request->has('willing_to_help'),
                'consent_given' => true,
                'consent_given_at' => now(),
                'consent_text' => __('I agree to share my information for community purposes'),
                'language_preference' => app()->getLocale(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);

            // Attach skills
            if ($request->has('skills')) {
                $skillsData = [];
                foreach ($request->skills as $skillId => $data) {
                    $skillsData[$skillId] = [
                        'experience_level' => $data['level'],
                        'years_experience' => $data['years'],
                        'description' => $data['description'] ?? null
                    ];
                }
                $member->skills()->sync($skillsData);
            }

            // Attach support areas
            if ($request->has('support_areas')) {
                $member->supportAreas()->sync($request->support_areas);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('Registration successful! Your registration number is: :number', 
                    ['number' => $member->registration_number]),
                'registration_number' => $member->registration_number
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => __('An error occurred. Please try again.')
            ], 500);
        }
    }

    private function validateRegistration(Request $request)
    {
        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|in:male,female,other,prefer_not_to_say',
            'date_of_birth' => 'nullable|date|before:today',
            'nationality' => 'required|string|max:100',
            'citizenship_status' => 'required|in:angolan,south_african,dual_citizenship,other',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'mobile_number' => 'required|string|max:20|unique:members',
            'email' => 'nullable|email|max:100|unique:members',
            'preferred_contact_method' => 'required|in:phone,whatsapp,email',
            'employment_status' => 'required|in:employed,self_employed,student,unemployed,retired',
            'profession' => 'nullable|string|max:100',
            'consent' => 'required|accepted',
        ];

        if ($request->citizenship_status === 'other') {
            $rules['other_citizenship'] = 'required|string|max:100';
        }

        if ($request->preferred_contact_method === 'whatsapp') {
            $rules['whatsapp_number'] = 'required|string|max:20';
        }

        $messages = [
            'consent.required' => __('You must agree to the data usage terms.'),
            'mobile_number.unique' => __('This phone number is already registered.'),
            'email.unique' => __('This email is already registered.'),
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

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
}