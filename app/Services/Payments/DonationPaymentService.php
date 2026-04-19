<?php

namespace App\Services\Payments;

use App\Models\DonationPayment;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DonationPaymentService
{
    public function __construct(private readonly SSLCommerzGateway $gateway)
    {
    }

    public function initiate(array $data, ?User $user = null): array
    {
        $payment = DB::transaction(function () use ($data, $user) {
            return DonationPayment::create([
                'user_id' => $user?->id,
                'order_id' => $this->generateOrderId(),
                'donor_name' => $data['donor_name'],
                'donor_email' => $data['donor_email'] ?? null,
                'donor_phone' => $data['donor_phone'],
                'amount' => $data['amount'],
                'currency' => config('services.sslcommerz.currency', 'BDT'),
                'purpose' => $data['purpose'] ?? null,
                'message' => $data['message'] ?? null,
                'gateway_name' => 'sslcommerz',
                'status' => 'pending',
            ]);
        });

        if ($this->isMockMode()) {
            $payment = $this->markAsPaidMock($payment, [
                'note' => 'Mock payment approved in local development mode.',
            ]);

            return [
                'success' => true,
                'payment' => $payment,
                'gateway_url' => route('payments.donations.show', $payment->order_id),
                'redirect_mode' => 'internal',
                'message' => 'Mock payment completed successfully.',
            ];
        }

        $gatewayResponse = $this->gateway->initiate($payment);

        $payment->fill([
            'gateway_payload' => $gatewayResponse['payload'] ?? null,
            'gateway_response' => $gatewayResponse['response'] ?? null,
            'gateway_url' => $gatewayResponse['gateway_url'] ?? null,
            'gateway_session_key' => $gatewayResponse['session_key'] ?? null,
            'gateway_validation_id' => $gatewayResponse['validation_id'] ?? null,
            'status' => $gatewayResponse['success'] ? 'pending' : 'failed',
            'fail_reason' => $gatewayResponse['success'] ? null : ($gatewayResponse['message'] ?? 'Unable to initialize payment.'),
        ])->save();

        return [
            'success' => (bool) ($gatewayResponse['success'] ?? false),
            'payment' => $payment->refresh(),
            'gateway_url' => $gatewayResponse['gateway_url'] ?? null,
            'redirect_mode' => 'external',
            'message' => $gatewayResponse['message'] ?? null,
        ];
    }

    public function findByOrderIdOrFail(string $orderId): DonationPayment
    {
        return DonationPayment::where('order_id', $orderId)->firstOrFail();
    }

    public function finalizeFromCallback(string $orderId, string $callbackStatus, array $payload): DonationPayment
    {
        if (blank($orderId)) {
            throw (new ModelNotFoundException())->setModel(DonationPayment::class);
        }

        $payment = $this->findByOrderIdOrFail($orderId);

        return match ($callbackStatus) {
            'success' => $this->markAsPaid($payment, $payload),
            'fail' => $this->markAsFailed($payment, $payload, 'Donation payment failed.'),
            'cancel' => $this->markAsCanceled($payment, $payload),
            'ipn' => $this->handleIpn($payment, $payload),
            default => $payment,
        };
    }

    protected function markAsPaid(DonationPayment $payment, array $payload): DonationPayment
    {
        $validationId = (string) data_get($payload, 'val_id', $payment->gateway_validation_id);
        $validation = $this->gateway->validate($validationId);

        if ($validation['success']) {
            $validationResponse = $validation['response'] ?? [];
            $payment->update([
                'status' => 'paid',
                'transaction_id' => data_get($validationResponse, 'bank_tran_id')
                    ?? data_get($validationResponse, 'tran_id')
                    ?? data_get($payload, 'bank_tran_id')
                    ?? data_get($payload, 'tran_id'),
                'gateway_validation_id' => $validationId,
                'gateway_response' => [
                    'callback' => $payload,
                    'validation' => $validationResponse,
                ],
                'paid_at' => now(),
                'canceled_at' => null,
                'fail_reason' => null,
            ]);

            return $payment->refresh();
        }

        return $this->markAsFailed(
            $payment,
            $payload,
            $validation['message'] ?? 'Payment validation failed.'
        );
    }

    protected function markAsFailed(DonationPayment $payment, array $payload, string $reason): DonationPayment
    {
        $payment->update([
            'status' => 'failed',
            'fail_reason' => $reason,
            'gateway_response' => [
                'callback' => $payload,
            ],
        ]);

        return $payment->refresh();
    }

    protected function markAsCanceled(DonationPayment $payment, array $payload): DonationPayment
    {
        $payment->update([
            'status' => 'canceled',
            'canceled_at' => now(),
            'gateway_response' => [
                'callback' => $payload,
            ],
        ]);

        return $payment->refresh();
    }

    protected function handleIpn(DonationPayment $payment, array $payload): DonationPayment
    {
        $status = strtolower((string) data_get($payload, 'status', data_get($payload, 'tran_status', '')));

        if (in_array($status, ['valid', 'validated', 'success', 'completed', 'paid'], true)) {
            return $this->markAsPaid($payment, $payload);
        }

        if (in_array($status, ['fail', 'failed', 'cancel', 'canceled', 'cancelled'], true)) {
            return $this->markAsFailed($payment, $payload, 'IPN reported a failed payment.');
        }

        return $payment->refresh();
    }

    protected function generateOrderId(): string
    {
        return Str::upper('DNT-'.now()->format('YmdHis').'-'.Str::random(6));
    }

    protected function isMockMode(): bool
    {
        return (bool) config('services.sslcommerz.mock_mode', false);
    }

    protected function markAsPaidMock(DonationPayment $payment, array $payload): DonationPayment
    {
        if ($payment->status === 'paid') {
            return $payment->refresh();
        }

        $payment->update([
            'status' => 'paid',
            'transaction_id' => 'MOCK-'.Str::upper(Str::random(10)),
            'gateway_response' => [
                'mock' => true,
                'callback' => $payload,
            ],
            'paid_at' => now(),
            'canceled_at' => null,
            'fail_reason' => null,
        ]);

        return $payment->refresh();
    }
}