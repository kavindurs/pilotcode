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
            // Add referred_by column if it doesn't exist
            if (!Schema::hasColumn('users', 'referred_by')) {
                $table->string('referred_by')->nullable();
            }

            $table->unsignedInteger('parent_referrer_id')->nullable();
            $table->unsignedInteger('referral_level')->default(1);
            $table->string('referral_path')->nullable(); // Store the complete referral chain

            $table->index(['parent_referrer_id']);
            $table->index(['referral_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['parent_referrer_id']);
            $table->dropIndex(['referral_level']);
            $table->dropColumn(['parent_referrer_id', 'referral_level', 'referral_path']);
        });
    }
};
