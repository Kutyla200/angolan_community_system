<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('member_support_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('support_area_id')->constrained('community_support_areas');
            $table->text('additional_info')->nullable();
            $table->timestamps();
            
            $table->unique(['member_id', 'support_area_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('member_support_areas');
    }
};