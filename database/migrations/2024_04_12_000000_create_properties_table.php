<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_type');
            $table->string('business_name');
            $table->string('city');
            $table->string('country');
            $table->string('zip_code');
            $table->string('annual_revenue');
            $table->string('employee_count');
            $table->string('category');
            $table->string('subcategory');
            $table->string('domain')->nullable();
            $table->string('business_email')->unique();
            $table->string('document_path')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('password');
            $table->string('status')->default('Not Approved');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
};
