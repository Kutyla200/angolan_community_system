<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];
    
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Get the admin user that performed the action
     */
    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }
    
    /**
     * Get action badge color
     */
    public function getActionBadgeColorAttribute(): string
    {
        return match($this->action) {
            'create' => 'success',
            'update' => 'info',
            'delete', 'bulk_delete' => 'danger',
            'view' => 'secondary',
            'export', 'export_csv', 'export_excel', 'export_pdf' => 'warning',
            'login' => 'primary',
            'failed_login' => 'danger',
            'logout' => 'secondary',
            'send_message' => 'info',
            'import' => 'success',
            default => 'light',
        };
    }
    
    /**
     * Get action icon
     */
    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'create' => 'bi-plus-circle',
            'update' => 'bi-pencil',
            'delete', 'bulk_delete' => 'bi-trash',
            'view' => 'bi-eye',
            'export', 'export_csv', 'export_excel', 'export_pdf' => 'bi-download',
            'login' => 'bi-box-arrow-in-right',
            'failed_login' => 'bi-x-circle',
            'logout' => 'bi-box-arrow-right',
            'send_message' => 'bi-envelope',
            'import' => 'bi-upload',
            'backup' => 'bi-hdd',
            'security_scan' => 'bi-shield-check',
            'clear_cache' => 'bi-arrow-clockwise',
            default => 'bi-circle',
        };
    }
    
    /**
     * Get formatted action name
     */
    public function getFormattedActionAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->action));
    }
    
    /**
     * Scope for recent logs
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
    
    /**
     * Scope for specific action type
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }
    
    /**
     * Scope for specific model type
     */
    public function scopeModelType($query, $type)
    {
        return $query->where('model_type', $type);
    }
    
    /**
     * Scope for failed login attempts
     */
    public function scopeFailedLogins($query)
    {
        return $query->where('action', 'failed_login');
    }
    
    /**
     * Get browser name from user agent
     */
    public function getBrowserAttribute(): string
    {
        if (!$this->user_agent) {
            return 'Unknown';
        }
        
        if (str_contains($this->user_agent, 'Chrome')) {
            return 'Chrome';
        } elseif (str_contains($this->user_agent, 'Firefox')) {
            return 'Firefox';
        } elseif (str_contains($this->user_agent, 'Safari')) {
            return 'Safari';
        } elseif (str_contains($this->user_agent, 'Edge')) {
            return 'Edge';
        } elseif (str_contains($this->user_agent, 'Opera')) {
            return 'Opera';
        }
        
        return 'Unknown';
    }
    
    /**
     * Get device type from user agent
     */
    public function getDeviceAttribute(): string
    {
        if (!$this->user_agent) {
            return 'Unknown';
        }
        
        if (str_contains($this->user_agent, 'Mobile')) {
            return 'Mobile';
        } elseif (str_contains($this->user_agent, 'Tablet')) {
            return 'Tablet';
        }
        
        return 'Desktop';
    }
}