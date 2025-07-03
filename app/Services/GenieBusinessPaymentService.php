<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenieBusinessPaymentService
{
    private $appId;
    private $appKey;
    private $baseUrl;

    public function __construct()
    {
        $this->appId = config('genie_business.app_id');
        $this->appKey = config('genie_business.app_key');
        $this->baseUrl = config('genie_business.api_url');
    }

    /**
     * Create a payment request
     */
    public function createPayment($amount, $description, $customerEmail, $customerName, $propertyId, $adId, $returnUrl = null)
    {
        // If in sandbox environment and getting forbidden errors, use simulation
        if (config('genie_business.environment') === 'sandbox') {
            return $this->simulatePayment($amount, $description, $customerEmail, $customerName, $propertyId, $adId, $returnUrl);
        }

        try {
            $paymentData = [
                'amount' => $amount,
                'currency' => config('genie_business.currency', 'LKR'), // Use LKR for Genie Business
                'description' => $description,
                'customerEmail' => $customerEmail,
                'customerName' => $customerName,
                'metadata' => json_encode([
                    'property_id' => $propertyId,
                    'ad_id' => $adId,
                    'type' => 'ad_promotion'
                ]),
                'returnUrl' => $returnUrl ?: route('property.ads.payment.callback'),
                'cancelUrl' => route('property.ads.payment.cancel')
            ];

            // Use the correct endpoint from config
            $endpoint = $this->baseUrl . config('genie_business.endpoints.transactions');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->appKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-App-ID' => $this->appId
            ])->timeout(30)->post($endpoint, $paymentData);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                Log::error('Genie Business Payment Creation Failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'payment_data' => $paymentData
                ]);

                return [
                    'success' => false,
                    'error' => 'Payment creation failed: ' . $response->body()
                ];
            }
        } catch (Exception $e) {
            Log::error('Genie Business Payment Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Payment service error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verify a payment
     */
    public function verifyPayment($paymentId)
    {
        // Handle sandbox transactions
        if (config('genie_business.environment') === 'sandbox' && strpos($paymentId, 'sandbox_') === 0) {
            return [
                'success' => true,
                'data' => [
                    'id' => $paymentId,
                    'status' => 'completed',
                    'amount' => 1000, // Default amount for sandbox
                    'currency' => config('genie_business.currency', 'LKR'),
                    'sandbox' => true
                ]
            ];
        }

        try {
            // Use the correct endpoint from config
            $endpoint = $this->baseUrl . config('genie_business.endpoints.transaction_status') . $paymentId;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->appKey,
                'Accept' => 'application/json',
                'X-App-ID' => $this->appId
            ])->timeout(30)->get($endpoint);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                Log::error('Genie Business Payment Verification Failed', [
                    'payment_id' => $paymentId,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return [
                    'success' => false,
                    'error' => 'Payment verification failed: ' . $response->body()
                ];
            }
        } catch (Exception $e) {
            Log::error('Genie Business Payment Verification Exception', [
                'payment_id' => $paymentId,
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Payment verification error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Process a refund
     */
    public function refundPayment($paymentId, $amount = null, $reason = null)
    {
        try {
            $refundData = [
                'payment_id' => $paymentId,
                'reason' => $reason ?: 'Ad promotion cancelled'
            ];

            if ($amount) {
                $refundData['amount'] = $amount;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->appKey,
                'Content-Type' => 'application/json',
                'X-App-ID' => $this->appId
            ])->post($this->baseUrl . '/refunds', $refundData);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                Log::error('Genie Business Refund Failed', [
                    'payment_id' => $paymentId,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return [
                    'success' => false,
                    'error' => 'Refund failed: ' . $response->body()
                ];
            }
        } catch (Exception $e) {
            Log::error('Genie Business Refund Exception', [
                'payment_id' => $paymentId,
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Refund error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Simulate payment for sandbox environment
     */
    private function simulatePayment($amount, $description, $customerEmail, $customerName, $propertyId, $adId, $returnUrl = null)
    {
        // Generate a mock transaction ID
        $transactionId = 'sandbox_' . time() . '_' . $adId;

        return [
            'success' => true,
            'data' => [
                'id' => $transactionId,
                'status' => 'pending',
                'amount' => $amount,
                'currency' => config('genie_business.currency', 'LKR'),
                'description' => $description,
                'customer_email' => $customerEmail,
                'customer_name' => $customerName,
                'payment_url' => config('app.url') . '/property/ads/' . $adId . '/payment/success?sandbox=true&transaction_id=' . $transactionId,
                'metadata' => [
                    'property_id' => $propertyId,
                    'ad_id' => $adId,
                    'type' => 'ad_promotion'
                ],
                'sandbox' => true
            ]
        ];
    }
}
