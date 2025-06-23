<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value');
            $table->string('type')->default('string'); // string, number, boolean, json
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default ad cost setting
        DB::table('admin_settings')->insert([
            'key' => 'ad_daily_cost',
            'value' => '1.00',
            'type' => 'number',
            'description' => 'Cost per day for ad promotions in USD',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_settings');
    }
};
