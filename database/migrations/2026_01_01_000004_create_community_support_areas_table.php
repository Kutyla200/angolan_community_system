<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('community_support_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_pt');
            $table->string('icon')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_pt')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('community_support_areas');
    }
};