<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];
    
    protected $casts = [
        'value' => 'json',
    ];
    
    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        // Clear cache when settings are updated
        static::saved(function () {
            Cache::forget('system_settings');
        });
        
        static::deleted(function () {
            Cache::forget('system_settings');
        });
    }
    
    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $settings = Cache::remember('system_settings', 3600, function () {
            return static::all()->pluck('value', 'key')->toArray();
        });
        
        return $settings[$key] ?? $default;
    }
    
    /**
     * Set a setting value
     */
    public static function set($key, $value, $type = 'string', $group = 'general', $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description,
            ]
        );
    }
    
    /**
     * Get all settings as key-value pairs
     */
    public static function getAllSettings()
    {
        return Cache::remember('system_settings', 3600, function () {
            return static::all()->pluck('value', 'key')->toArray();
        });
    }
    
    /**
     * Get settings by group
     */
    public static function getByGroup($group)
    {
        return static::where('group', $group)->get()->pluck('value', 'key')->toArray();
    }
    
    /**
     * Check if setting exists
     */
    public static function has($key)
    {
        return static::where('key', $key)->exists();
    }
    
    /**
     * Remove a setting
     */
    public static function remove($key)
    {
        return static::where('key', $key)->delete();
    }
}