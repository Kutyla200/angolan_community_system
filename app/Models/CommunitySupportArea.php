<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunitySupportArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_pt',
        'icon',
        'description_en',
        'description_pt',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function members()
    {
        return $this->belongsToMany(Member::class, 'member_support_areas')
                    ->withPivot('additional_info')
                    ->withTimestamps();
    }

    // Localization
    public function getNameAttribute()
    {
        return app()->getLocale() === 'pt' ? $this->name_pt : $this->name_en;
    }

    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'pt' ? $this->description_pt : $this->description_en;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name_en');
    }
}