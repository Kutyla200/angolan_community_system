<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->string('group')->default('general'); // general, email, security, etc.
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['key', 'group']);
        });
        
        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'site_name',
                'value' => '"Angolan Community"',
                'type' => 'string',
                'group' => 'general',
                'description' => 'The name of the community platform',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'default_language',
                'value' => '"en"',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Default language for the platform',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'timezone',
                'value' => '"Africa/Johannesburg"',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Server timezone',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'registration_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Allow new member registrations',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Site maintenance mode',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'new_member_notifications',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'notifications',
                'description' => 'Notify admins of new member registrations',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'daily_summary',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'notifications',
                'description' => 'Send daily activity summary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'weekly_report',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'notifications',
                'description' => 'Send weekly statistics report',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'security_two_factor_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'Require 2FA for admin accounts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'security_session_timeout',
                'value' => '60',
                'type' => 'integer',
                'group' => 'security',
                'description' => 'Session timeout in minutes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'security_max_login_attempts',
                'value' => '5',
                'type' => 'integer',
                'group' => 'security',
                'description' => 'Maximum failed login attempts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'privacy_popia_compliance',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'privacy',
                'description' => 'POPIA compliance enabled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'privacy_data_retention_days',
                'value' => '90',
                'type' => 'integer',
                'group' => 'privacy',
                'description' => 'Data retention period in days',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'backup_automatic_backups',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'backup',
                'description' => 'Enable automatic backups',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'backup_frequency',
                'value' => '"daily"',
                'type' => 'string',
                'group' => 'backup',
                'description' => 'Backup frequency',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};