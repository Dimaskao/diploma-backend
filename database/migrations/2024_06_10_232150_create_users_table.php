<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->text('avatar_url')->nullable();
            $table->uuid('role_id');
            $table->text('skills_description')->nullable();
            $table->text('experience')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('regular_users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->unique(['company_id', 'user_id']);
        });

        // Add the check constraint using raw SQL
        DB::statement('ALTER TABLE users ADD CONSTRAINT check_company_or_user CHECK (company_id IS NOT NULL OR user_id IS NOT NULL)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the check constraint using raw SQL
            DB::statement('ALTER TABLE users DROP CONSTRAINT check_company_or_user');
        });

        Schema::dropIfExists('users');
    }
};
