<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'registration_number',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'nationality',
        'citizenship_status',
        'other_citizenship',
        'province',
        'city',
        'area',
        'mobile_number',
        'email',
        'preferred_contact_method',
        'whatsapp_number',
        'employment_status',
        'profession',
        'field_of_study',
        'willing_to_help',
        'consent_given',
        'consent_given_at',
        'consent_text',
        'language_preference',
        'registered_at',
        'last_updated_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'consent_given' => 'boolean',
        'willing_to_help' => 'boolean',
        'consent_given_at' => 'datetime',
        'registered_at' => 'datetime',
        'last_updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($member) {
            $member->registration_number = 'ANG' . date('Ym') . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            $member->registered_at = now();
        });

        static::updating(function ($member) {
            $member->last_updated_at = now();
        });
    }

    // Relationships
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'member_skills')
                    ->withPivot('experience_level', 'years_experience', 'description')
                    ->withTimestamps();
    }

// In app/Models/Member.php

public function supportAreas()
{
    // Fix: Explicitly define the foreign keys 'member_id' and 'support_area_id'
    return $this->belongsToMany(CommunitySupportArea::class, 'member_support_areas', 'member_id', 'support_area_id')
                ->withPivot('additional_info')
                ->withTimestamps();
}

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeByProvince($query, $province)
    {
        return $query->where('province', $province);
    }

    public function scopeWillingToHelp($query)
    {
        return $query->where('willing_to_help', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('mobile_number', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('registration_number', 'like', "%{$search}%");
        });
    }

    // Helpers
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? now()->diffInYears($this->date_of_birth) : null;
    }

    public function getContactInfoAttribute()
    {
        switch ($this->preferred_contact_method) {
            case 'whatsapp':
                return $this->whatsapp_number ?: $this->mobile_number;
            case 'email':
                return $this->email;
            default:
                return $this->mobile_number;
        }
    }
}