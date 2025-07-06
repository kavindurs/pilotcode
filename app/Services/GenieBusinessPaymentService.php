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
        $this->appId = env('GENIE_BUSINESS_APP_ID');
        $this->appKey = env('GENIE_BUSINESS_APP_KEY');
        $this->baseUrl = env('GENIE_BUSINESS_API_URL');
    }

    /**
     * Create a payment request using Genie Business API
     */
    public function createPayment($amount, $description, $customerEmail, $customerName, $propertyId, $adId, $returnUrl = null)
    {
        try {
            // Convert amount from LKR to cents (Genie expects amount in cents)
            $amountInCents = (int) ($amount * 300);

            $payload = [
                'amount' => $amountInCents,
                'currency' => 'LKR',
                'localId' => "AD_{$adId}_" . time(),
                'customerReference' => $customerName ?? "Property_{$propertyId}",
            ];

            // Add callback URLs carefully (Genie Business may reject localhost URLs)
            if ($returnUrl) {
                if (str_contains($returnUrl, '127.0.0.1') || str_contains($returnUrl, 'localhost')) {
                    // For localhost development, skip redirectUrl to avoid API rejection
                    // The user will need to manually return to the success page
                    Log::info('Skipping localhost redirect URL to avoid API rejection', ['returnUrl' => $returnUrl]);
                } else {
                    // Production URL, safe to add
                    $payload['redirectUrl'] = $returnUrl;
                    Log::info('Adding redirect URL to payment', ['redirectUrl' => $returnUrl]);
                }
            }

            Log::info('Genie Business Payment Request', [
                'endpoint' => $this->baseUrl . '/public/v2/transactions',
                'payload' => $payload,
                'headers' => [
                    'Authorization' => substr($this->appKey, 0, 20) . '...',
                    'Content-Type' => 'application/json'
                ]
            ]);

            $response = Http::withHeaders([
                'Authorization' => $this->appKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(30)->post($this->baseUrl . '/public/v2/transactions', $payload);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('Genie Business Payment Response', ['response' => $data]);

                return [
                    'success' => true,
                    'data' => [
                        'id' => $data['id'] ?? null,
                        'payment_url' => $data['url'] ?? null,
                        'status' => $data['status'] ?? 'pending',
                        'amount' => $amountInCents,
                        'currency' => 'LKR',
                        'local_id' => $payload['localId'],
                        'sandbox' => false // Real Genie Business transaction
                    ]
                ];
            } else {
                Log::error('Genie Business Payment Creation Failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'payload' => $payload
                ]);

                return [
                    'success' => false,
                    'error' => 'Payment gateway error: ' . $response->body()
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
     * Verify a payment using Genie Business API
     */
    public function verifyPayment($transactionId)
    {
        // Handle sandbox transactions
        if (strpos($transactionId, 'sandbox_') === 0) {
            return [
                'success' => true,
                'data' => [
                    'id' => $transactionId,
                    'status' => 'completed',
                    'amount' => 0,
                    'currency' => 'USD',
                    'sandbox' => true
                ]
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->appKey,
                'Accept' => 'application/json',
            ])->timeout(30)->get($this->baseUrl . "/public/v2/transactions/{$transactionId}");

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success' => true,
                    'data' => $data
                ];
            } else {
                Log::error('Genie Business Payment Verification Failed', [
                    'transaction_id' => $transactionId,
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
                'transaction_id' => $transactionId,
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
    public function refundPayment($transactionId, $amount = null, $reason = null)
    {
        try {
            $payload = [
                'reason' => $reason ?: 'Admin initiated refund'
            ];

            if ($amount) {
                $payload['amount'] = (int) ($amount * 100); // Convert to cents
            }

            $response = Http::withHeaders([
                'Authorization' => $this->appKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(30)->post($this->baseUrl . "/v2/transactions/{$transactionId}/refund", $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                Log::error('Genie Business Refund Failed', [
                    'transaction_id' => $transactionId,
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
                'transaction_id' => $transactionId,
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Refund error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Simulate payment for sandbox environment (fallback)
     */
    private function simulatePayment($amount, $description, $customerEmail, $customerName, $propertyId, $adId, $returnUrl = null)
    {
        // Generate a mock transaction ID
        $transactionId = 'sandbox_' . time() . '_' . $adId;

        $paymentUrl = config('app.url') . "/property/ads/{$adId}/payment/success?" . http_build_query([
            'sandbox' => 'true',
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'currency' => 'LKR'
        ]);

        Log::info('Sandbox payment simulation', [
            'transaction_id' => $transactionId,
            'payment_url' => $paymentUrl,
            'amount' => $amount
        ]);

        return [
            'success' => true,
            'data' => [
                'id' => $transactionId,
                'payment_url' => $paymentUrl,
                'status' => 'pending',
                'amount' => $amount,
                'currency' => 'LKR',
                'sandbox' => true
            ]
        ];
    }
}
