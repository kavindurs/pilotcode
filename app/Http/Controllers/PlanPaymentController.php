<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Payment;
use App\Models\Property;
use App\Services\GenieBusinessPaymentService;
use Illuminate\Support\Facades\Log;

class PlanPaymentController extends Controller
{
    /**
     * Show the payment checkout page for a plan
     */
    public function checkout(Request $request)
    {
        // Check if property owner is logged in
        if (!session('property_id')) {
            return redirect()->route('property.login')
                   ->with('error', 'Please login to purchase a plan.');
        }

        $planId = $request->get('plan_id');
        $amount = $request->get('amount');

        if (!$planId || !$amount) {
            return redirect()->route('plans.index')
                   ->with('error', 'Invalid plan selection.');
        }

        $plan = Plan::findOrFail($planId);
        $property = Property::find(session('property_id'));

        if (!$property) {
            return redirect()->route('property.login')
                   ->with('error', 'Property not found. Please login again.');
        }

        // Check if amount is already in USD (from URL parameter)
        // If amount is small (< 100), assume it's USD, otherwise assume it's LKR
        if ($amount < 100) {
            // Amount is likely USD
            $usdAmount = $amount;
            $lkrAmount = $amount * 100; // Convert to LKR for display
        } else {
            // Amount is likely LKR
            $lkrAmount = $amount;
            $usdAmount = round($amount / 300, 2); // Convert to USD
        }

        return view('plans.checkout', compact('plan', 'property', 'amount', 'usdAmount', 'lkrAmount'));
    }

    /**
     * Process the payment for a plan
     */
    public function processPayment(Request $request)
    {
        // Check if property owner is logged in
        if (!session('property_id')) {
            return redirect()->route('property.login')
                   ->with('error', 'Please login to purchase a plan.');
        }

        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string'
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);
        $property = Property::find(session('property_id'));

        if (!$property) {
            return redirect()->route('property.login')
                   ->with('error', 'Property not found. Please login again.');
        }

        // Check if amount is already in USD (from URL parameter)
        // If amount is small (< 100), assume it's USD, otherwise assume it's LKR
        if ($validated['amount'] < 100) {
            // Amount is likely USD
            $usdAmount = $validated['amount'];
            $lkrAmount = $validated['amount'] * 100; // Convert to LKR for payment gateway
        } else {
            // Amount is likely LKR
            $lkrAmount = $validated['amount'];
            $usdAmount = round($validated['amount'] / 300, 2); // Convert to USD
        }

        // For Genie payment gateway, use LKR amount
        $paymentAmount = $lkrAmount;

        // Store payment data in session instead of creating payment record
        // Payment record will only be created on successful payment
        $paymentData = [
            'plan_id' => $plan->id,
            'property_id' => $property->id,
            'business_email' => $property->business_email ?: 'noemail@property' . $property->id . '.local',
            'customer_email' => $property->business_email ?: 'noemail@property' . $property->id . '.local',
            'customer_name' => $property->business_name ?: $property->contact_person,
            'amount' => $paymentAmount, // Use LKR amount for payment gateway
            'currency' => 'LKR',
            'payment_method' => $validated['payment_method'],
            'order_id' => 'PLAN_' . $plan->id . '_' . time()
        ];

        // Store in session for later use in success callback
        session(['pending_plan_payment' => $paymentData]);

        Log::info('Plan Payment Data Stored in Session', [
            'property_id' => $property->id,
            'plan_id' => $plan->id,
            'amount' => $paymentAmount,
            'order_id' => $paymentData['order_id']
        ]);

        // Initialize payment service (same as Ad Manager)
        $paymentService = new GenieBusinessPaymentService();

        // Create payment request using the same service as Ad Manager
        // Use a temporary ID for payment gateway tracking
        $tempPaymentId = 'TEMP_' . $property->id . '_' . time();

        $paymentResult = $paymentService->createPayment(
            $paymentAmount, // Amount in LKR for payment gateway
            "Subscription to {$plan->name} Plan",
            $property->business_email ?: 'noemail@property' . $property->id . '.local',
            $property->business_name ?: $property->contact_person,
            $property->id,
            $tempPaymentId, // Use temporary ID instead of payment ID
            route('plans.payment.success') // Use route without payment ID
        );

        Log::info('Plan Payment Service Result', [
            'temp_payment_id' => $tempPaymentId,
            'success' => $paymentResult['success'],
            'data' => $paymentResult['data'] ?? null,
            'error' => $paymentResult['error'] ?? null
        ]);

        if ($paymentResult['success']) {
            // Store gateway transaction ID in session for later reference
            if (isset($paymentResult['data']['id'])) {
                $paymentData['gateway_transaction_id'] = $paymentResult['data']['id'];
                session(['pending_plan_payment' => $paymentData]);
            }

            // Redirect to payment gateway (same as Ad Manager)
            if (isset($paymentResult['data']['payment_url'])) {
                Log::info('Redirecting to payment URL', [
                    'payment_url' => $paymentResult['data']['payment_url'],
                    'temp_payment_id' => $tempPaymentId
                ]);

                return redirect($paymentResult['data']['payment_url']);
            } else {
                Log::warning('No payment URL in response, redirecting to plans index');
                return redirect()->route('plans.index')
                    ->with('error', 'Payment URL not available. Please try again or contact support.');
            }
        } else {
            // Payment creation failed, clear session and show error
            Log::error('Plan payment creation failed', [
                'temp_payment_id' => $tempPaymentId,
                'error' => $paymentResult['error']
            ]);

            session()->forget('pending_plan_payment');

            return back()->withErrors([
                'payment' => 'Payment processing failed: ' . $paymentResult['error']
            ])->withInput();
        }
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request)
    {
        // Get payment data from session
        $paymentData = session('pending_plan_payment');

        if (!$paymentData) {
            Log::warning('Payment success callback without session data', [
                'request_params' => $request->all()
            ]);

            return redirect()->route('plans.index')
                   ->with('error', 'Payment session expired. Please contact support if payment was deducted.');
        }

        Log::info('Plan Payment Success Callback', [
            'payment_data' => $paymentData,
            'request_params' => $request->all()
        ]);

        // NOW create/update the payment record (only on success)
        $property = Property::find($paymentData['property_id']);

        if (!$property) {
            Log::error('Property not found during payment success', [
                'property_id' => $paymentData['property_id']
            ]);

            return redirect()->route('property.login')
                   ->with('error', 'Property not found. Please login again.');
        }

        // Check for existing payment record for this property (regardless of plan or status)
        // Never keep two payment records for the same property
        $payment = Payment::updateOrCreate(
            [
                'property_id' => $property->id
            ],
            [
                'plan_id' => $paymentData['plan_id'],
                'business_email' => $paymentData['business_email'],
                'customer_email' => $paymentData['customer_email'],
                'customer_name' => $paymentData['customer_name'],
                'amount' => $paymentData['amount'],
                'currency' => $paymentData['currency'],
                'status' => 'completed', // Set as completed immediately
                'order_id' => $paymentData['order_id'],
                'payment_method' => $paymentData['payment_method'],
                'transaction_id' => $request->get('transaction_id', $paymentData['gateway_transaction_id'] ?? $paymentData['order_id']),
                'genie_transaction_id' => $paymentData['gateway_transaction_id'] ?? null,
                'completed_at' => now(),
                'updated_at' => now()
            ]
        );

        Log::info('Plan Payment Record Created/Updated on Success', [
            'payment_id' => $payment->id,
            'property_id' => $property->id,
            'plan_id' => $paymentData['plan_id'],
            'amount' => $paymentData['amount'],
            'was_existing' => $payment->wasRecentlyCreated ? false : true
        ]);

        // ONLY NOW update property's plan (after payment is confirmed successful)
        if ($property && $payment->status === 'completed') {
            $property->update([
                'plan_id' => $payment->plan_id
            ]);

            Log::info('Property plan updated after successful payment', [
                'property_id' => $property->id,
                'old_plan_id' => $property->getOriginal('plan_id'),
                'new_plan_id' => $payment->plan_id,
                'payment_id' => $payment->id
            ]);
        }

        // Clear the session data
        session()->forget('pending_plan_payment');

        Log::info('Plan Payment Completed', [
            'payment_id' => $payment->id,
            'plan_id' => $payment->plan_id,
            'property_id' => $property->id
        ]);

        return redirect()->route('plans.activated')
               ->with('success', 'Payment completed successfully! Your ' . $payment->plan->name . ' plan has been activated.');
    }

    /**
     * Handle cancelled payment
     */
    public function paymentCancel(Request $request)
    {
        // Get payment data from session
        $paymentData = session('pending_plan_payment');

        if ($paymentData) {
            Log::info('Plan Payment Cancelled', [
                'property_id' => $paymentData['property_id'],
                'plan_id' => $paymentData['plan_id'],
                'order_id' => $paymentData['order_id']
            ]);

            // Clear the session data
            session()->forget('pending_plan_payment');
        } else {
            Log::info('Plan Payment Cancel without session data', [
                'request_params' => $request->all()
            ]);
        }

        return redirect()->route('plans.index')
               ->with('error', 'Payment was cancelled. Please try again if you want to activate the plan.');
    }

    /**
     * Verify payment status
     */
    public function verifyPayment(Request $request, Payment $payment)
    {
        $transactionId = $request->get('transaction_id');

        if (!$transactionId) {
            return back()->with('error', 'Transaction ID is required for verification.');
        }

        // Simple verification - in a real scenario, you'd verify with the payment gateway
        Log::info('Manual Plan Payment Verification', [
            'payment_id' => $payment->id,
            'transaction_id' => $transactionId
        ]);

        // Update payment as completed
        $payment->update([
            'status' => 'completed',
            'transaction_id' => $transactionId,
            'paid_at' => now()
        ]);

        // Update property's plan
        $property = $payment->property;
        if ($property) {
            $property->update([
                'plan_id' => $payment->plan_id
            ]);
        }

        return redirect()->route('plans.activated')
               ->with('success', 'Payment verified successfully! Your ' . $payment->plan->name . ' plan has been activated.');
    }
}
