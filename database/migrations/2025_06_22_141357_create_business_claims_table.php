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
        Schema::create('business_claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id'); // The property being claimed

            // Business Details
            $table->string('business_name');
            $table->string('business_email');
            $table->enum('property_type', ['web', 'physical']);

            // Claimant Personal Details
            $table->string('first_name');
            $table->string('last_name');

            // Location Details
            $table->string('zip_code')->nullable();
            $table->string('country');

            // Business Size Details
            $table->string('annual_revenue')->nullable();
            $table->string('employee_count')->nullable();

            // Category Details
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id')->nullable();

            // Type-specific fields
            $table->string('domain')->nullable(); // For web properties
            $table->string('business_document')->nullable(); // For physical properties (file path)

            // Claim Status and Review
            $table->enum('status', ['Pending', 'Under Review', 'Approved', 'Rejected'])->default('Pending');
            $table->text('admin_notes')->nullable(); // Notes from admin review
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable(); // Admin who reviewed
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['property_id', 'status']);
            $table->index('business_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_claims');
    }
};
