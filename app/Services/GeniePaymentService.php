<?php
// filepath: c:\xampp\htdocs\pilot\app\Services\GeniePaymentService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeniePaymentService
{
    private function getApiHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . config('genie_business.app_key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    // Function to create a transaction
    public function createTransaction($amount, $currency, $redirectUrl, $webhookUrl, $customerData)
    {
        // Use sandbox environment for testing
        if (config('genie_business.environment') === 'sandbox') {
            return $this->createMockTransaction($amount, $currency, $redirectUrl, $webhookUrl, $customerData);
        }

        // REAL API IMPLEMENTATION
        try {
            Log::info('Creating Genie Business transaction', [
                'amount' => $amount,
                'currency' => $currency,
                'customer' => $customerData,
                'api_url' => config('genie_business.api_url') . config('genie_business.endpoints.transactions')
            ]);

            $payload = [
                'amount' => (int)$amount,
                'currency' => $currency,
                'redirectUrl' => $redirectUrl,
                'webhook' => $webhookUrl,
                'customer' => $customerData,
                'paymentType' => 'UNSCHEDULED',
            ];

            $response = Http::withHeaders($this->getApiHeaders())
                ->timeout(30)
                ->post(config('genie_business.api_url') . config('genie_business.endpoints.transactions'), $payload);

            Log::info('Genie Business API Response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['transactionId']) && isset($responseData['paymentLink'])) {
                    return [
                        'success' => true,
                        'data' => $responseData
                    ];
                }
            }

            return [
                'success' => false,
                'error' => 'Payment gateway error: ' . $response->status() . ' - ' . $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('Exception in createTransaction', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Payment service unavailable: ' . $e->getMessage()
            ];
        }
    }

    // Mock implementation for testing
    private function createMockTransaction($amount, $currency, $redirectUrl, $webhookUrl, $customerData)
    {
        Log::info('MOCK: Creating Genie Business transaction', [
            'amount' => $amount,
            'currency' => $currency,
            'customer' => $customerData,
            'redirectUrl' => $redirectUrl
        ]);

        // Generate mock transaction data
        $mockTransactionId = 'MOCK_TXN_' . time() . '_' . rand(1000, 9999);
        $mockPaymentLink = route('mock.payment.page', ['transaction_id' => $mockTransactionId]);

        return [
            'success' => true,
            'data' => [
                'transactionId' => $mockTransactionId,
                'paymentLink' => $mockPaymentLink,
                'status' => 'PENDING',
                'amount' => $amount,
                'currency' => $currency
            ]
        ];
    }

    // Function to get transaction status
    public function getTransactionStatus($transactionId)
    {
        // Mock implementation for testing
        if (str_starts_with($transactionId, 'MOCK_TXN_')) {
            return [
                'transactionId' => $transactionId,
                'status' => 'CONFIRMED',
                'amount' => 1000,
                'currency' => 'LKR'
            ];
        }

        // Real implementation
        try {
            $response = Http::withHeaders($this->getApiHeaders())
                ->timeout(30)
                ->get(config('genie_business.api_url') . config('genie_business.endpoints.transaction_status') . $transactionId);

            if ($response->successful()) {
                return $response->json();
            } else {
                return [
                    'error' => 'Failed to get transaction status',
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => 'Service unavailable: ' . $e->getMessage()
            ];
        }
    }
}
