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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Free, Basic, Pro, Premium
            $table->decimal('price', 8, 2); // 0 for free, otherwise the dollar amount
            $table->integer('product_limit'); // Maximum products allowed
            $table->integer('widget_limit');  // Maximum widgets allowed
            $table->integer('html_integration_limit'); // Character limit for HTML integration
            $table->integer('review_invitation_limit'); // Number of review invitation emails allowed
            $table->boolean('referral_code')->default(false); // Whether a referral code is provided
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
