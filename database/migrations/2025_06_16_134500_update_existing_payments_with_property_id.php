<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Payment;
use App\Models\Property;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing payment records to include property_id
        // where property_id is null but business_email matches a property

        $payments = Payment::whereNull('property_id')
            ->whereNotNull('business_email')
            ->get();

        foreach ($payments as $payment) {
            $property = Property::where('business_email', $payment->business_email)->first();
            if ($property) {
                $payment->update(['property_id' => $property->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible as we're updating existing data
        // based on business logic, not schema changes
    }
};
