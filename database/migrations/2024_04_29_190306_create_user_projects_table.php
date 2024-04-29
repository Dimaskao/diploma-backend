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
        Schema::create('user_projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('desc');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string('url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_projects');
    }
};
