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
        Schema::create('job_offer_skills', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('job_offer_id');
            $table->uuid('skill_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('job_offers')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offer_skills');
    }
};
