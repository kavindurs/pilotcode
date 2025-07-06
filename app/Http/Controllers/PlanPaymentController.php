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

        // Create a payment record
        $payment = Payment::create([
            'plan_id' => $plan->id,
            'property_id' => $property->id,
            'business_email' => $property->business_email ?: 'noemail@property' . $property->id . '.local',
            'customer_email' => $property->business_email ?: 'noemail@property' . $property->id . '.local',
            'customer_name' => $property->business_name ?: $property->contact_person,
            'amount' => $paymentAmount*3, // Use LKR amount for payment gateway
            'currency' => 'LKR',
            'status' => 'pending',
            'order_id' => 'PLAN_' . $plan->id . '_' . time(),
            'payment_method' => $validated['payment_method']
        ]);

        // Initialize payment service (same as Ad Manager)
        $paymentService = new GenieBusinessPaymentService();

        // Create payment request using the same service as Ad Manager
        $paymentResult = $paymentService->createPayment(
            $paymentAmount, // Amount in LKR for payment gateway
            "Subscription to {$plan->name} Plan",
            $property->business_email ?: 'noemail@property' . $property->id . '.local',
            $property->business_name ?: $property->contact_person,
            $property->id,
            $payment->id, // Use payment ID instead of ad ID
            route('plans.payment.success', $payment->id)
        );

        Log::info('Plan Payment Service Result', [
            'payment_id' => $payment->id,
            'success' => $paymentResult['success'],
            'data' => $paymentResult['data'] ?? null,
            'error' => $paymentResult['error'] ?? null
        ]);

        if ($paymentResult['success']) {
            // Store payment ID and redirect to payment page
            $payment->update([
                'transaction_id' => $paymentResult['data']['id'] ?? null,
                'genie_transaction_id' => $paymentResult['data']['id'] ?? null
            ]);

            // Redirect to payment gateway (same as Ad Manager)
            if (isset($paymentResult['data']['payment_url'])) {
                Log::info('Redirecting to payment URL', [
                    'payment_url' => $paymentResult['data']['payment_url'],
                    'payment_id' => $payment->id
                ]);

                return redirect($paymentResult['data']['payment_url']);
            } else {
                Log::warning('No payment URL in response, redirecting to plans index');
                return redirect()->route('plans.index')
                    ->with('error', 'Payment URL not available. Please try again or contact support.');
            }
        } else {
            // Payment creation failed, delete the payment record and show error
            Log::error('Plan payment creation failed, deleting payment', [
                'payment_id' => $payment->id,
                'error' => $paymentResult['error']
            ]);
            $payment->delete();

            return back()->withErrors([
                'payment' => 'Payment processing failed: ' . $paymentResult['error']
            ])->withInput();
        }
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request, Payment $payment)
    {
        Log::info('Plan Payment Success Callback', [
            'payment_id' => $payment->id,
            'plan_id' => $payment->plan_id,
            'request_params' => $request->all()
        ]);

        // Update payment status
        $payment->update([
            'status' => 'completed',
            'transaction_id' => $request->get('transaction_id', $payment->order_id),
            'completed_at' => now()
        ]);

        // Update property's plan
        $property = $payment->property;
        if ($property) {
            $property->update([
                'plan_id' => $payment->plan_id
            ]);
        }

        Log::info('Plan Payment Completed', [
            'payment_id' => $payment->id,
            'plan_id' => $payment->plan_id,
            'property_id' => $property->id ?? null
        ]);

        return redirect()->route('plans.activated')
               ->with('success', 'Payment completed successfully! Your ' . $payment->plan->name . ' plan has been activated.');
    }

    /**
     * Handle cancelled payment
     */
    public function paymentCancel(Request $request, Payment $payment)
    {
        Log::info('Plan Payment Cancelled', [
            'payment_id' => $payment->id,
            'plan_id' => $payment->plan_id
        ]);

        // Update payment status
        $payment->update([
            'status' => 'cancelled'
        ]);

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
