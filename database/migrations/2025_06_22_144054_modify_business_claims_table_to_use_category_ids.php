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
        Schema::table('business_claims', function (Blueprint $table) {
            // Add new ID columns
            $table->unsignedBigInteger('category_id')->nullable()->after('employee_count');
            $table->unsignedBigInteger('subcategory_id')->nullable()->after('category_id');

            // Add foreign key constraints
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade');
        });

        // Remove old string columns after adding new ones
        Schema::table('business_claims', function (Blueprint $table) {
            $table->dropColumn(['category', 'subcategory']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_claims', function (Blueprint $table) {
            // Add back string columns
            $table->string('category')->after('employee_count');
            $table->string('subcategory')->nullable()->after('category');
        });

        Schema::table('business_claims', function (Blueprint $table) {
            // Drop foreign keys and ID columns
            $table->dropForeign(['category_id']);
            $table->dropForeign(['subcategory_id']);
            $table->dropColumn(['category_id', 'subcategory_id']);
        });
    }
};
