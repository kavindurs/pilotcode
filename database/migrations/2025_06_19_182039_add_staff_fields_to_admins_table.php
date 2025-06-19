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
        Schema::table('admins', function (Blueprint $table) {
            // Add phone number field
            $table->string('phone_number')->nullable()->after('email');

            // Add profile picture field
            $table->string('profile_picture')->nullable()->after('phone_number');

            // Add role field with enum values
            $table->enum('role', ['super_admin', 'admin', 'editor', 'accountant', 'worker'])
                  ->default('admin')->after('profile_picture');

            // Update status enum to include 'suspended'
            $table->enum('status', ['active', 'inactive', 'suspended'])
                  ->default('active')->change();

            // Add last login timestamp
            $table->timestamp('last_login_at')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            // Drop the added columns
            $table->dropColumn(['phone_number', 'profile_picture', 'role', 'last_login_at']);

            // Revert status enum to original values
            $table->enum('status', ['active', 'inactive'])->default('active')->change();
        });
    }
};
