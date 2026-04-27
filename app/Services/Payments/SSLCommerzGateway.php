<?php

namespace App\Services\Payments;

use App\Models\DonationPayment;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SSLCommerzGateway
{
    public function isConfigured(): bool
    {
        return filled(config('services.sslcommerz.store_id')) && filled(config('services.sslcommerz.store_password'));
    }

    public function preparePayload(DonationPayment $payment): array
    {
        $currency = (string) config('services.sslcommerz.currency', 'BDT');

        return [
            'store_id' => config('services.sslcommerz.store_id'),
            'store_passwd' => config('services.sslcommerz.store_password'),
            'total_amount' => number_format((float) $payment->amount, 2, '.', ''),
            'currency' => $currency,
            'tran_id' => $payment->order_id,
            'success_url' => route('payments.donations.success'),
            'fail_url' => route('payments.donations.fail'),
            'cancel_url' => route('payments.donations.cancel'),
            'ipn_url' => route('payments.donations.ipn'),
            'cus_name' => $payment->donor_name,
            'cus_email' => $payment->donor_email ?? 'donor@example.com',
            'cus_add1' => 'Neighbourly Donation',
            'cus_add2' => $payment->purpose ?? 'Community donation',
            'cus_city' => 'Dhaka',
            'cus_state' => 'Dhaka',
            'cus_postcode' => '1000',
            'cus_country' => 'Bangladesh',
            'cus_phone' => $payment->donor_phone,
            'shipping_method' => 'No',
            'product_name' => $payment->purpose ?: 'Donation',
            'product_category' => 'Donation',
            'product_profile' => 'non-physical-goods',
            'value_a' => $payment->order_id,
            'value_b' => $payment->donor_email ?? '',
            'value_c' => (string) $payment->user_id,
            'value_d' => Str::limit((string) ($payment->message ?? ''), 50, ''),
        ];
    }

    public function initiate(DonationPayment $payment): array
    {
        $payload = $this->preparePayload($payment);

        return $this->initiateWithPayload($payload);
    }

    public function initiateWithPayload(array $payload): array
    {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'payload' => $payload,
                'message' => 'SSLCommerz credentials are not configured.',
            ];
        }

        try {
            $response = Http::asForm()
                ->timeout(30)
                ->post($this->gatewayEndpoint(), $payload);

            $body = $response->json();
            $gatewayUrl = data_get($body, 'GatewayPageURL')
                ?? data_get($body, 'redirectGatewayURL')
                ?? data_get($body, 'gatewayPageURL');

            return [
                'success' => $response->successful() && filled($gatewayUrl),
                'gateway_url' => $gatewayUrl,
                'session_key' => data_get($body, 'sessionkey') ?? data_get($body, 'sessionKey'),
                'validation_id' => data_get($body, 'val_id') ?? data_get($body, 'validation_id'),
                'payload' => $payload,
                'response' => $body ?? ['raw' => $response->body()],
                'message' => data_get($body, 'failedreason')
                    ?? data_get($body, 'failed_reason')
                    ?? 'Unable to initialize SSLCommerz payment.',
            ];
        } catch (ConnectionException $exception) {
            return [
                'success' => false,
                'payload' => $payload,
                'message' => $exception->getMessage(),
            ];
        }
    }

    public function validate(?string $validationId): array
    {
        if (! $this->isConfigured() || blank($validationId)) {
            return [
                'success' => false,
                'message' => 'Missing SSLCommerz validation details.',
            ];
        }

        try {
            $response = Http::asForm()
                ->timeout(30)
                ->get($this->validationEndpoint(), [
                    'val_id' => $validationId,
                    'store_id' => config('services.sslcommerz.store_id'),
                    'store_passwd' => config('services.sslcommerz.store_password'),
                    'format' => 'json',
                ]);

            $body = $response->json();
            $status = strtoupper((string) data_get($body, 'status'));

            return [
                'success' => $response->successful() && in_array($status, ['VALID', 'VALIDATED'], true),
                'response' => $body ?? ['raw' => $response->body()],
                'message' => data_get($body, 'failedreason')
                    ?? data_get($body, 'failed_reason')
                    ?? 'Unable to validate the payment.',
            ];
        } catch (ConnectionException $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage(),
            ];
        }
    }

    protected function gatewayEndpoint(): string
    {
        return config('services.sslcommerz.sandbox', true)
            ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'
            : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';
    }

    protected function validationEndpoint(): string
    {
        return config('services.sslcommerz.sandbox', true)
            ? 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php'
            : 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php';
    }
}