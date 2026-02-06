<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'admin_user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
        'url',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class);
    }

    // Scopes
    public function scopeForModel($query, $modelType, $modelId = null)
    {
        $query->where('model_type', $modelType);
        if (!is_null($modelId)) {
            $query->where('model_id', $modelId);
        }
        return $query;
    }

    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }
}
