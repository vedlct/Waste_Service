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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 40)->default('admin')->after('password');
            $table->string('status', 40)->default('active')->after('role');
            $table->timestamp('last_login_at')->nullable()->after('status');

            $table->index(['role', 'status']);
            $table->index('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role', 'status']);
            $table->dropIndex(['last_login_at']);
            $table->dropColumn(['role', 'status', 'last_login_at']);
        });
    }
};
