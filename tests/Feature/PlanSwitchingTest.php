<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Plan;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class PlanSwitchingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test plans
        Plan::create([
            'name' => 'Free',
            'price' => 0.00,
            'product_limit' => 0,
            'widget_limit' => 0,
            'html_integration_limit' => 100,
            'review_invitation_limit' => 0,
            'referral_code' => false,
        ]);

        Plan::create([
            'name' => 'Basic',
            'price' => 19.00,
            'product_limit' => 2,
            'widget_limit' => 2,
            'html_integration_limit' => 250,
            'review_invitation_limit' => 30,
            'referral_code' => false,
        ]);

        Plan::create([
            'name' => 'Pro',
            'price' => 59.00,
            'product_limit' => 5,
            'widget_limit' => 5,
            'html_integration_limit' => 500,
            'review_invitation_limit' => 75,
            'referral_code' => true,
        ]);
    }

    public function test_customer_can_have_only_one_active_plan()
    {
        $customerEmail = 'test@example.com';
        $basicPlan = Plan::where('name', 'Basic')->first();
        $proPlan = Plan::where('name', 'Pro')->first();

        // Create initial payment for Basic plan
        $payment1 = Payment::create([
            'business_email' => $customerEmail,
            'plan_id' => $basicPlan->id,
            'order_id' => 'ORDER_001',
            'amount' => 19.00,
            'currency' => 'USD',
            'status' => 'confirmed',
            'payment_method' => 'genie_business',
            'customer_name' => 'Test Customer',
            'customer_email' => $customerEmail,
        ]);

        // Verify customer has Basic plan active
        $activePlan = Payment::getActivePlanForCustomer($customerEmail);
        $this->assertEquals($basicPlan->id, $activePlan->plan_id);

        // Switch to Pro plan - this should update the existing record
        $payment1->update([
            'plan_id' => $proPlan->id,
            'amount' => 59.00,
        ]);

        // Verify customer now has Pro plan active and only one active payment
        $activePlan = Payment::getActivePlanForCustomer($customerEmail);
        $activePaymentsCount = Payment::where('business_email', $customerEmail)->active()->count();

        $this->assertEquals($proPlan->id, $activePlan->plan_id);
        $this->assertEquals(1, $activePaymentsCount);
    }

    public function test_existing_payment_record_is_updated_when_switching_plans()
    {
        $customerEmail = 'test@example.com';
        $propertyId = 123;
        $basicPlan = Plan::where('name', 'Basic')->first();
        $proPlan = Plan::where('name', 'Pro')->first();

        // Create initial payment for Basic plan
        $initialPayment = Payment::create([
            'property_id' => $propertyId,
            'business_email' => $customerEmail,
            'plan_id' => $basicPlan->id,
            'order_id' => 'ORDER_001',
            'amount' => 19.00,
            'currency' => 'LKR',
            'status' => 'confirmed',
            'payment_method' => 'genie_business',
            'customer_name' => 'Test Customer',
            'customer_email' => $customerEmail,
        ]);

        // Verify initial state
        $this->assertEquals(1, Payment::count());
        $this->assertEquals($basicPlan->id, $initialPayment->plan_id);

        // Simulate switching to Pro plan (this should update the existing record)
        $existingPayment = Payment::findExistingPaymentForProperty($customerEmail, $propertyId);
        $this->assertNotNull($existingPayment);
        $this->assertEquals($initialPayment->id, $existingPayment->id);

        // Update the existing payment to Pro plan
        $existingPayment->update([
            'plan_id' => $proPlan->id,
            'amount' => 59.00,
            'status' => 'pending', // Reset status for new payment
            'order_id' => 'ORDER_002',
        ]);

        // Verify that we still have only one payment record
        $this->assertEquals(1, Payment::count());

        // Verify the payment was updated, not created new
        $updatedPayment = Payment::first();
        $this->assertEquals($initialPayment->id, $updatedPayment->id);
        $this->assertEquals($proPlan->id, $updatedPayment->plan_id);
        $this->assertEquals(59.00, $updatedPayment->amount);
        $this->assertEquals('ORDER_002', $updatedPayment->order_id);
    }

    public function test_findExistingPaymentForProperty_prioritizes_property_id()
    {
        $customerEmail = 'test@example.com';
        $propertyId = 123;
        $basicPlan = Plan::where('name', 'Basic')->first();

        // Create payment with property_id
        $paymentWithProperty = Payment::create([
            'property_id' => $propertyId,
            'business_email' => $customerEmail,
            'plan_id' => $basicPlan->id,
            'order_id' => 'ORDER_WITH_PROPERTY',
            'amount' => 19.00,
            'currency' => 'LKR',
            'status' => 'confirmed',
            'payment_method' => 'genie_business',
            'customer_name' => 'Test Customer',
            'customer_email' => $customerEmail,
        ]);

        // Create another payment with same email but different property_id
        $paymentWithoutProperty = Payment::create([
            'property_id' => null,
            'business_email' => $customerEmail,
            'plan_id' => $basicPlan->id,
            'order_id' => 'ORDER_WITHOUT_PROPERTY',
            'amount' => 19.00,
            'currency' => 'LKR',
            'status' => 'confirmed',
            'payment_method' => 'genie_business',
            'customer_name' => 'Test Customer',
            'customer_email' => $customerEmail,
        ]);

        // When searching by property_id, should return the payment with property_id
        $found = Payment::findExistingPaymentForProperty($customerEmail, $propertyId);
        $this->assertEquals($paymentWithProperty->id, $found->id);
        $this->assertEquals('ORDER_WITH_PROPERTY', $found->order_id);

        // When searching without property_id, should return first matching email
        $foundByEmail = Payment::findExistingPaymentForProperty($customerEmail, null);
        $this->assertNotNull($foundByEmail);
        $this->assertEquals($customerEmail, $foundByEmail->business_email);
    }

    public function test_pending_payments_are_cancelled_when_new_payment_is_confirmed()
    {
        $customerEmail = 'test@example.com';
        $basicPlan = Plan::where('name', 'Basic')->first();
        $proPlan = Plan::where('name', 'Pro')->first();

        // Create pending payment for Basic plan
        Payment::create([
            'business_email' => $customerEmail,
            'plan_id' => $basicPlan->id,
            'order_id' => 'ORDER_001',
            'amount' => 19.00,
            'currency' => 'USD',
            'status' => 'pending',
            'payment_method' => 'genie_business',
            'customer_name' => 'Test Customer',
            'customer_email' => $customerEmail,
        ]);

        // Create another pending payment for Pro plan
        Payment::create([
            'business_email' => $customerEmail,
            'plan_id' => $proPlan->id,
            'order_id' => 'ORDER_002',
            'amount' => 59.00,
            'currency' => 'USD',
            'status' => 'pending',
            'payment_method' => 'genie_business',
            'customer_name' => 'Test Customer',
            'customer_email' => $customerEmail,
        ]);

        // Confirm the Pro plan payment (simulate webhook)
        $proPayment = Payment::where('order_id', 'ORDER_002')->first();
        $proPayment->update(['status' => 'confirmed']);

        // Simulate the webhook logic that cancels other pending payments
        Payment::where('business_email', $customerEmail)
            ->where('id', '!=', $proPayment->id)
            ->whereIn('status', ['pending', 'PENDING'])
            ->update(['status' => 'cancelled']);

        // Verify only Pro plan is active and Basic plan payment is cancelled
        $activePlan = Payment::getActivePlanForCustomer($customerEmail);
        $cancelledPayments = Payment::where('business_email', $customerEmail)->cancelled()->count();

        $this->assertEquals($proPlan->id, $activePlan->plan_id);
        $this->assertEquals(1, $cancelledPayments);
    }
}
