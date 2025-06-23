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
        Schema::table('ads_simple', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->after('end_date')->nullable(); // Total amount
            $table->integer('days')->after('amount')->nullable(); // Number of days
            $table->decimal('daily_rate', 8, 2)->after('days')->default(1.00); // $1 per day
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->after('daily_rate')->default('pending');
            $table->string('payment_intent_id')->after('payment_status')->nullable(); // Stripe payment intent ID
            $table->string('transaction_id')->after('payment_intent_id')->nullable(); // Stripe transaction ID
            $table->timestamp('paid_at')->after('transaction_id')->nullable();
            $table->text('payment_notes')->after('paid_at')->nullable(); // For payment errors or admin notes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads_simple', function (Blueprint $table) {
            $table->dropColumn([
                'amount',
                'days',
                'daily_rate',
                'payment_status',
                'payment_intent_id',
                'transaction_id',
                'paid_at',
                'payment_notes'
            ]);
        });
    }
};
