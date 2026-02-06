<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_pt',
        'category',
        'icon',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function members()
    {
        return $this->belongsToMany(Member::class, 'member_skills')
                    ->withPivot('experience_level', 'years_experience', 'description');
    }

    // Localization
    public function getNameAttribute()
    {
        return app()->getLocale() === 'pt' ? $this->name_pt : $this->name_en;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name_en');
    }
}