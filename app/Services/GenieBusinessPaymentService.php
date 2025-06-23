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
        $this->appId = '36bafce7-a201-429b-a9e2-c5b78546677c';
        $this->appKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcHBJZCI6IjM2YmFmY2U3LWEyMDEtNDI5Yi1hOWUyLWM1Yjc4NTQ2Njc3YyIsImNvbXBhbnlJZCI6IjYzOTdmMzlkZjA3ZmJhMDAwODQyYTkwYiIsImlhdCI6MTY3MDkwMjY4NSwiZXhwIjo0ODI2NTc2Mjg1fQ.fy12dgFhA3iB_RCjD7y8j5HClNRZUiBZgAg-QzFpxaE';
        $this->baseUrl = 'https://api.geniebusiness.com'; // Replace with actual API base URL
    }

    /**
     * Create a payment request
     */
    public function createPayment($amount, $description, $customerEmail, $customerName, $propertyId, $adId, $returnUrl = null)
    {
        try {
            $paymentData = [
                'amount' => $amount,
                'currency' => 'USD', // Assuming US dollars
                'description' => $description,
                'customer' => [
                    'email' => $customerEmail,
                    'name' => $customerName
                ],
                'metadata' => [
                    'property_id' => $propertyId,
                    'ad_id' => $adId,
                    'type' => 'ad_promotion'
                ],
                'return_url' => $returnUrl ?: route('property.ads.payment.callback'),
                'cancel_url' => route('property.ads.payment.cancel')
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->appKey,
                'Content-Type' => 'application/json',
                'X-App-ID' => $this->appId
            ])->post($this->baseUrl . '/payments', $paymentData);

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
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->appKey,
                'X-App-ID' => $this->appId
            ])->get($this->baseUrl . '/payments/' . $paymentId);

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
}
