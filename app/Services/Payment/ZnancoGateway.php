<?php

namespace App\Services\Payment;

class ZnancoGateway
{
    private string $baseUrl;
    private string $merchantId;
    private string $apiKey;
    private bool $sandbox;

    public function __construct()
    {
        $this->baseUrl = config('services.zananco.base_url', 'https://billmaster.zananco.com/api');
        $this->merchantId = config('services.zananco.merchant_id', '');
        $this->apiKey = config('services.zananco.api_key', '');
        $this->sandbox = config('services.zananco.sandbox', false);
    }

    /**
     * Initialize a payment request with Zananco BillMaster
     */
    public function initiatePayment(
        string $amount,
        string $description,
        string $customerName,
        string $customerEmail,
        string $phoneNumber,
        string $reference = ''
    ): array {
        $payload = [
            'merchant_id' => $this->merchantId,
            'amount' => $amount,
            'currency' => 'ZMW',
            'description' => $description,
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $phoneNumber,
            'reference' => $reference ?: uniqid('PAY_'),
            'return_url' => route('payments.callback'),
            'sandbox' => $this->sandbox,
        ];

        // Sign the request
        $signature = $this->generateSignature($payload);
        $payload['signature'] = $signature;

        try {
            $response = \Http::post("{$this->baseUrl}/initiate", $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'payment_url' => $response->json('payment_url'),
                    'transaction_id' => $response->json('transaction_id'),
                    'response' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('message', 'Payment initiation failed'),
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify a payment with Zananco BillMaster
     */
    public function verifyPayment(string $transactionId): array
    {
        $payload = [
            'merchant_id' => $this->merchantId,
            'transaction_id' => $transactionId,
        ];

        $signature = $this->generateSignature($payload);
        $payload['signature'] = $signature;

        try {
            $response = \Http::post("{$this->baseUrl}/verify", $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'status' => $data['status'] ?? 'unknown',
                    'amount' => $data['amount'] ?? null,
                    'paid_at' => $data['paid_at'] ?? null,
                    'response' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('message', 'Verification failed'),
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate HMAC signature for request
     */
    private function generateSignature(array $payload): string
    {
        // Remove signature from payload if it exists
        unset($payload['signature']);

        // Sort by keys and create query string
        ksort($payload);
        $queryString = http_build_query($payload);

        // Generate HMAC-SHA256 signature
        return hash_hmac('sha256', $queryString, $this->apiKey);
    }

    /**
     * Verify webhook signature from Zananco
     */
    public function verifyWebhookSignature(array $data, string $signature): bool
    {
        $payload = $data;
        unset($payload['signature']);

        ksort($payload);
        $queryString = http_build_query($payload);
        $expectedSignature = hash_hmac('sha256', $queryString, $this->apiKey);

        return hash_equals($expectedSignature, $signature);
    }
}
