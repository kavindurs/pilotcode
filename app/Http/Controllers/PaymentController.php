<?php

namespace App\Http\Controllers;

use App\Services\GeniePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Plan;
use App\Models\Payment;
use Illuminate\Support\Str;
use App\Http\Controllers\ReferralController;
use App\Models\Property;

class PaymentController extends Controller
{
    protected $geniePaymentService;

    public function __construct(GeniePaymentService $geniePaymentService)
    {
        $this->geniePaymentService = $geniePaymentService;
    }

    // Show the payment page (for testing, not part of the checkout flow)
    public function showPaymentPage()
    {
        $orderId = 'ItemNo12345';
        $amount  = 1000.00;
        $currency = 'USD';
        $return_url = route('payment.success');
        $cancel_url = route('payment.cancel');
        $hash = $this->generateHash($orderId, $amount, $currency);
        return view('plans.payment.page', compact('hash', 'return_url', 'cancel_url', 'orderId', 'amount'));
    }

    // Generate the hash for the payment using your merchant secret.
    public function generateHash($orderId, $amount, $currency)
    {
        $merchant_id = '1226143';
        $merchant_secret = 'NDk0MDQ4ODg5NjA4MDUwNDAzMzQxMTQ1Nzk5NjI2ODAyNjE5NzA=';

        $hash = strtoupper(
            md5(
                $merchant_id .
                $orderId .
                number_format($amount, 2, '.', '') .
                $currency .
                strtoupper(md5($merchant_secret))
            )
        );
        return $hash;
    }

    // Handle PayHere payment notifications.
    public function paymentNotify(Request $request)
    {
        $merchant_id      = $request->merchant_id;
        $order_id         = $request->order_id;
        $payhere_amount   = $request->payhere_amount;
        $payhere_currency = $request->payhere_currency;
        $status_code      = $request->status_code;
        $md5sig           = $request->md5sig;

        $merchant_secret = 'NDk0MDQ4ODg5NjA4MDUwNDAzMzQxMTQ1Nzk5NjI2ODAyNjE5NzA=';
        $local_md5sig = strtoupper(
            md5(
                $merchant_id .
                $order_id .
                $payhere_amount .
                $payhere_currency .
                $status_code .
                strtoupper(md5($merchant_secret))
            )
        );

        if ($local_md5sig === $md5sig && $status_code == 2) {
            $payment = Payment::where('order_id', $order_id)->first();
            if ($payment) {
                $payment->status = 'success';
                $payment->transaction_id = $request->payment_id;
                $payment->completed_at = now();
                $payment->save();

                // Process referral commission if payment is successful
                $this->processReferralCommission($payment);

                // Cancel any other pending payments for the same customer
                Payment::where('business_email', $payment->business_email)
                    ->where('id', '!=', $payment->id)
                    ->whereIn('status', ['pending', 'PENDING'])
                    ->update(['status' => 'cancelled']);
            }
            return redirect()->route('payment.success');
        } else {
            $payment = Payment::where('order_id', $order_id)->first();
            if ($payment) {
                $payment->status = 'failed';
                $payment->save();
            }
            return redirect()->route('payment.cancel');
        }
    }

    public function paymentSuccess()
    {
        return view('plans.payment.success');
    }

    public function paymentCancel()
    {
        return view('plans.payment.cancel');
    }

    // NEW: Genie Business checkout method - Direct payment processing
    public function checkout(Request $request)
    {
        $request->validate([
            'customer_email' => 'required|email|max:255',
            'customer_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'plan_id' => 'required|exists:plans,id',
        ]);

        try {
            $plan = Plan::findOrFail($request->plan_id);

            // Generate unique order ID
            $orderId = 'GENIE_' . Str::random(10) . '_' . time();

            // Convert amount to LKR for Genie Business API (assuming price is in USD)
            $amountInLKR = $request->amount * 300; // Convert USD to LKR (approximate rate)
            $currency = 'LKR';

            // URLs for redirects and webhooks
            $redirectUrl = route('payment.success');
            $webhookUrl = route('payment.webhook');

            // Customer data from form
            $customerData = [
                'name' => $request->customer_name,
                'email' => $request->customer_email,
                'billingEmail' => $request->customer_email,
                'billingAddress1' => '123 Street',
                'billingCity' => 'Colombo',
                'billingCountry' => 'Sri Lanka',
                'billingPostCode' => '12345',
            ];

            Log::info('Starting Genie Business payment process', [
                'plan_id' => $plan->id,
                'order_id' => $orderId,
                'amount_usd' => $request->amount,
                'amount_lkr' => $amountInLKR,
                'customer' => $customerData
            ]);

            // Get the property ID from session if available
            $propertyId = session('property_id', null);

            Log::info('Payment checkout initiated', [
                'customer_email' => $request->customer_email,
                'property_id' => $propertyId,
                'plan_id' => $plan->id,
                'amount' => $request->amount
            ]);

            // Check for existing payment record for this property/customer
            $existingPayment = Payment::findExistingPaymentForProperty($request->customer_email, $propertyId);

            // Cancel any other pending payments for this customer before creating/updating
            Payment::where('business_email', $request->customer_email)
                ->whereIn('status', ['pending', 'PENDING'])
                ->update(['status' => 'cancelled']);

            if ($existingPayment) {
                // Update existing payment record (regardless of status) instead of creating new one
                $payment = $existingPayment;
                $payment->update([
                    'property_id' => $propertyId, // Ensure property ID is set
                    'plan_id' => $plan->id, // Update to new plan
                    'order_id' => $orderId,
                    'amount' => $request->amount,
                    'currency' => $currency,
                    'status' => 'pending',
                    'payment_method' => 'genie_business',
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'genie_transaction_id' => null, // Reset previous transaction ID
                    'payment_url' => null, // Reset previous payment URL
                    'completed_at' => null, // Reset completion time
                ]);
            } else {
                // Create new payment record only if no existing record found for this property/customer
                $payment = Payment::create([
                    'user_id' => null,
                    'property_id' => $propertyId, // Store property ID from session
                    'business_email' => $request->customer_email,
                    'plan_id' => $plan->id,
                    'order_id' => $orderId,
                    'amount' => $request->amount,
                    'currency' => $currency,
                    'status' => 'pending',
                    'payment_method' => 'genie_business',
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                ]);
            }

            // Call Genie Business API to create transaction
            $result = $this->geniePaymentService->createTransaction(
                $amountInLKR,
                $currency,
                $redirectUrl,
                $webhookUrl,
                $customerData
            );

            // Check if transaction was created successfully
            if ($result['success'] && isset($result['data']['transactionId'])) {
                // Update payment with Genie transaction ID
                $payment->update([
                    'genie_transaction_id' => $result['data']['transactionId'],
                    'payment_url' => $result['data']['paymentLink']
                ]);

                Log::info('Genie Business transaction created successfully', [
                    'transaction_id' => $result['data']['transactionId'],
                    'payment_link' => $result['data']['paymentLink']
                ]);

                // Redirect to Genie Business payment portal
                return redirect()->away($result['data']['paymentLink']);
            }

            // Handle error if transaction creation fails
            return redirect()->back()->withErrors([
                'payment' => 'Failed to create transaction: ' . ($result['error'] ?? 'Unknown error')
            ]);

        } catch (\Exception $e) {
            Log::error('Genie Business payment failed', [
                'error' => $e->getMessage(),
                'plan_id' => $request->plan_id
            ]);

            return redirect()->back()->withErrors([
                'payment' => 'Payment initiation failed: ' . $e->getMessage()
            ]);
        }
    }

    // Method to handle the webhook from Genie Business
    public function handleWebhook(Request $request)
    {
        $data = $request->all();

        Log::info('Genie Business Webhook received', $data);

        // Update payment status based on webhook data
        if (isset($data['transactionId'])) {
            $payment = Payment::where('genie_transaction_id', $data['transactionId'])->first();

            if ($payment) {
                $status = strtolower($data['status'] ?? 'unknown');
                $payment->update([
                    'status' => $status,
                    'completed_at' => $status === 'confirmed' ? now() : null
                ]);

                // If payment is confirmed, cancel any other pending payments for the same customer
                if ($status === 'confirmed') {
                    Payment::where('business_email', $payment->business_email)
                        ->where('id', '!=', $payment->id)
                        ->whereIn('status', ['pending', 'PENDING'])
                        ->update(['status' => 'cancelled']);
                }

                Log::info('Payment status updated', [
                    'payment_id' => $payment->id,
                    'status' => $status
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    // Method to check the status of the transaction
    public function checkTransactionStatus($transactionId)
    {
        try {
            $transactionStatus = $this->geniePaymentService->getTransactionStatus($transactionId);
            return response()->json($transactionStatus);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show checkout page
     */
    public function showCheckout(Request $request)
    {
        // Check if property owner is logged in using session
        if (!session('property_id')) {
            return redirect()->route('property.login')
                   ->with('error', 'Please login to access checkout.');
        }

        $planId = $request->query('plan_id');
        $amount = $request->query('amount');

        // Validate required parameters
        if (!$planId) {
            return redirect()->route('plans.index')->withErrors([
                'error' => 'Plan ID is required'
            ]);
        }

        // Find the plan
        try {
            $plan = \App\Models\Plan::findOrFail($planId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('plans.index')->withErrors([
                'error' => 'Plan not found'
            ]);
        }

        // If amount is not provided, use plan price
        if (!$amount) {
            $amount = $plan->price;
        }

        $customerData = [
            'name' => '',
            'email' => '',
        ];

        return view('payments.checkout', compact(
            'plan',
            'planId',
            'amount',
            'customerData'
        ));
    }

    /**
     * Process referral commission when payment is successful
     */
    private function processReferralCommission($payment)
    {
        try {
            // Find the property associated with this payment
            $property = Property::where('business_email', $payment->business_email)->first();

            if (!$property || !$property->referred_by) {
                return; // No referral code associated with this property
            }

            // Find the referral record for this referral code
            $referral = \App\Models\Referral::where('referral_code', $property->referred_by)->first();

            if (!$referral) {
                return; // No referral found with this code
            }

            // Get the referrer (the user who owns this referral code)
            $referrer = $referral->user;

            if (!$referrer) {
                return; // No user found for this referral
            }

            // Process the referral commission
            ReferralController::processReferral(
                $property->referred_by,
                null, // No specific referred user for property referrals
                $property->id,
                $payment->plan_id,
                $payment->amount
            );

            Log::info('Referral commission processed', [
                'referral_code' => $property->referred_by,
                'property_id' => $property->id,
                'payment_amount' => $payment->amount,
                'referrer_user_id' => $referrer->id,
                'referrer_name' => $referrer->name
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing referral commission: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
