<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable(); // Add user relation
            $table->unsignedBigInteger('property_id')->nullable(); // Add property relation
            $table->string('business_email');
            $table->unsignedBigInteger('plan_id');
            $table->string('order_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('LKR');
            $table->string('status')->default('pending');
            $table->string('transaction_id')->nullable();

            // New Genie Business specific fields
            $table->string('payment_method')->default('genie_business');
            $table->string('genie_customer_id')->nullable();
            $table->string('genie_transaction_id')->nullable();
            $table->text('payment_url')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->boolean('tokenize')->default(false);
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            // Add indexes
            $table->index(['user_id', 'status']);
            $table->index('genie_transaction_id');
            $table->index('business_email');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
