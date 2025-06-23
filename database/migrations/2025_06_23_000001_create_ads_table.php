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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->string('title');
            $table->text('description');
            $table->string('target_url')->nullable();
            $table->string('image_path')->nullable();
            $table->enum('ad_type', ['banner', 'featured', 'promoted'])->default('banner');
            $table->enum('placement', ['homepage', 'category', 'search_results', 'property_details'])->default('homepage');
            $table->enum('status', ['pending', 'approved', 'rejected', 'active', 'paused', 'expired'])->default('pending');
            $table->decimal('budget', 10, 2)->default(0);
            $table->decimal('cost_per_click', 10, 2)->default(0);
            $table->integer('total_clicks')->default(0);
            $table->integer('total_views')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('admins')->onDelete('set null');

            $table->index(['property_id', 'status']);
            $table->index(['status', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
