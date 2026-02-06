<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            
            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say']);
            $table->date('date_of_birth')->nullable();
            $table->string('nationality')->default('Angolan');
            $table->enum('citizenship_status', [
                'angolan', 
                'south_african', 
                'dual_citizenship', 
                'other'
            ]);
            $table->string('other_citizenship')->nullable();
            
            // Location Information
            $table->string('province');
            $table->string('city');
            $table->string('area')->nullable();
            
            // Contact Information
            $table->string('mobile_number')->unique();
            $table->string('email')->unique()->nullable();
            $table->enum('preferred_contact_method', ['phone', 'whatsapp', 'email']);
            $table->string('whatsapp_number')->nullable();
            
            // Professional Information
            $table->enum('employment_status', [
                'employed', 
                'self_employed', 
                'student', 
                'unemployed', 
                'retired'
            ]);
            $table->string('profession')->nullable();
            $table->string('field_of_study')->nullable();
            $table->boolean('willing_to_help')->default(false);
            
            // Consent & Privacy
            $table->boolean('consent_given')->default(false);
            $table->timestamp('consent_given_at')->nullable();
            $table->text('consent_text')->nullable();
            
            // System fields
            $table->string('language_preference')->default('en');
            $table->timestamp('registered_at')->useCurrent();

            $table->timestamp('last_updated_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['province', 'city']);
            $table->index('employment_status');
            $table->index('willing_to_help');
            $table->index('registered_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('members');
    }
};