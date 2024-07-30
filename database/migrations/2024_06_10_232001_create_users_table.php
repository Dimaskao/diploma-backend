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
            $table->uuid('id')->primary()->default(DB::raw('UUID()'));
            $table->string('email')->unique();
            $table->string('password');
            $table->text('avatar_url')->nullable();
            $table->uuid('role_id');
            $table->uuid('profileable_id')->nullable();
            $table->string('profileable_type')->nullable();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
            $table->unique(['profileable_id', 'profileable_type']);
        });

        DB::statement('ALTER TABLE users ADD CONSTRAINT check_profileable CHECK (profileable_id IS NOT NULL AND profileable_type IS NOT NULL)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement('ALTER TABLE users DROP CONSTRAINT check_profileable');
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'profileable_id', 'profileable_type']);
        });

        Schema::dropIfExists('users');
    }
};
