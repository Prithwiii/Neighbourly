<?php

namespace App\Services\Payments;

use App\Models\DonationPost;
use App\Models\DonationTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DonationPostPaymentService
{
    public function __construct(private readonly SSLCommerzGateway $gateway)
    {
    }

    public function initiate(DonationPost $post, array $data, ?User $user = null): array
    {
        if (! $post->isApproved() || ! $post->isActive()) {
            return [
                'success' => false,
                'message' => 'This donation request is not open for funding.',
            ];
        }

        $transaction = DB::transaction(function () use ($post, $data, $user) {
            return DonationTransaction::create([
                'donation_post_id' => $post->id,
                'user_id' => $user?->id,
                'order_id' => $this->generateOrderId(),
                'donor_name' => $data['donor_name'] ?? $user?->name ?? 'Anonymous Donor',
                'donor_email' => $data['donor_email'] ?? $user?->email,
                'donor_phone' => $data['donor_phone'] ?? '',
                'amount' => $data['amount'],
                'currency' => config('services.sslcommerz.currency', 'BDT'),
                'payment_method' => $data['payment_method'] ?? 'card',
                'status' => 'pending',
                'gateway_name' => 'sslcommerz',
            ]);
        });

        if ($this->isMockMode()) {
            $transaction = $this->markAsPaidMock($transaction, [
                'note' => 'Mock payment approved in local development mode.',
            ]);

            return [
                'success' => true,
                'transaction' => $transaction,
                'gateway_url' => route('payments.posts.receipt', $transaction->order_id),
                'redirect_mode' => 'internal',
                'message' => 'Mock payment completed successfully.',
            ];
        }

        $payload = $this->buildPayload($transaction, $post);
        $gatewayResponse = $this->gateway->initiateWithPayload($payload);

        $transaction->fill([
            'gateway_payload' => $gatewayResponse['payload'] ?? $payload,
            'gateway_response' => $gatewayResponse['response'] ?? null,
            'gateway_url' => $gatewayResponse['gateway_url'] ?? null,
            'gateway_session_key' => $gatewayResponse['session_key'] ?? null,
            'gateway_validation_id' => $gatewayResponse['validation_id'] ?? null,
            'status' => $gatewayResponse['success'] ? 'pending' : 'failed',
            'fail_reason' => $gatewayResponse['success'] ? null : ($gatewayResponse['message'] ?? 'Unable to initialize payment.'),
        ])->save();

        return [
            'success' => (bool) ($gatewayResponse['success'] ?? false),
            'transaction' => $transaction->refresh(),
            'gateway_url' => $gatewayResponse['gateway_url'] ?? null,
            'redirect_mode' => 'external',
            'message' => $gatewayResponse['message'] ?? null,
        ];
    }

    public function finalizeFromCallback(string $orderId, string $callbackStatus, array $payload): DonationTransaction
    {
        if (blank($orderId)) {
            throw (new ModelNotFoundException())->setModel(DonationTransaction::class);
        }

        $transaction = DonationTransaction::where('order_id', $orderId)->firstOrFail();

        return match ($callbackStatus) {
            'success' => $this->markAsPaid($transaction, $payload),
            'fail' => $this->markAsFailed($transaction, $payload, 'Donation payment failed.'),
            'cancel' => $this->markAsCanceled($transaction, $payload),
            'ipn' => $this->handleIpn($transaction, $payload),
            default => $transaction,
        };
    }

    public function findByOrderIdOrFail(string $orderId): DonationTransaction
    {
        return DonationTransaction::with(['donationPost', 'user'])
            ->where('order_id', $orderId)
            ->firstOrFail();
    }

    protected function markAsPaid(DonationTransaction $transaction, array $payload): DonationTransaction
    {
        if ($transaction->status === 'paid') {
            return $transaction->refresh();
        }

        $validationId = (string) data_get($payload, 'val_id', $transaction->gateway_validation_id);
        $validation = $this->gateway->validate($validationId);

        if (! $validation['success']) {
            return $this->markAsFailed(
                $transaction,
                $payload,
                $validation['message'] ?? 'Payment validation failed.'
            );
        }

        $validationResponse = $validation['response'] ?? [];

        DB::transaction(function () use ($transaction, $payload, $validationId, $validationResponse) {
            $transaction->update([
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

            $post = $transaction->donationPost()->lockForUpdate()->first();
            $newRaisedAmount = (float) $post->raised_amount + (float) $transaction->amount;

            $post->update([
                'raised_amount' => $newRaisedAmount,
                'status' => $newRaisedAmount >= (float) $post->goal_amount ? 'completed' : 'active',
            ]);
        });

        return $transaction->refresh();
    }

    protected function markAsFailed(DonationTransaction $transaction, array $payload, string $reason): DonationTransaction
    {
        $transaction->update([
            'status' => 'failed',
            'fail_reason' => $reason,
            'gateway_response' => [
                'callback' => $payload,
            ],
        ]);

        return $transaction->refresh();
    }

    protected function markAsCanceled(DonationTransaction $transaction, array $payload): DonationTransaction
    {
        $transaction->update([
            'status' => 'canceled',
            'canceled_at' => now(),
            'gateway_response' => [
                'callback' => $payload,
            ],
        ]);

        return $transaction->refresh();
    }

    protected function handleIpn(DonationTransaction $transaction, array $payload): DonationTransaction
    {
        $status = strtolower((string) data_get($payload, 'status', data_get($payload, 'tran_status', '')));

        if (in_array($status, ['valid', 'validated', 'success', 'completed', 'paid'], true)) {
            return $this->markAsPaid($transaction, $payload);
        }

        if (in_array($status, ['fail', 'failed', 'cancel', 'canceled', 'cancelled'], true)) {
            return $this->markAsFailed($transaction, $payload, 'IPN reported a failed payment.');
        }

        return $transaction->refresh();
    }

    protected function buildPayload(DonationTransaction $transaction, DonationPost $post): array
    {
        return [
            'store_id' => config('services.sslcommerz.store_id'),
            'store_passwd' => config('services.sslcommerz.store_password'),
            'total_amount' => number_format((float) $transaction->amount, 2, '.', ''),
            'currency' => (string) config('services.sslcommerz.currency', 'BDT'),
            'tran_id' => $transaction->order_id,
            'success_url' => route('payments.posts.success'),
            'fail_url' => route('payments.posts.fail'),
            'cancel_url' => route('payments.posts.cancel'),
            'ipn_url' => route('payments.posts.ipn'),
            'cus_name' => $transaction->donor_name,
            'cus_email' => $transaction->donor_email ?: 'donor@example.com',
            'cus_add1' => 'Neighbourly Donation',
            'cus_add2' => $post->location ?: 'Bangladesh',
            'cus_city' => 'Dhaka',
            'cus_state' => 'Dhaka',
            'cus_postcode' => '1000',
            'cus_country' => 'Bangladesh',
            'cus_phone' => $transaction->donor_phone ?: '01700000000',
            'shipping_method' => 'No',
            'product_name' => Str::limit($post->title, 40),
            'product_category' => 'Donation',
            'product_profile' => 'non-physical-goods',
            'value_a' => (string) $post->id,
            'value_b' => $transaction->payment_method,
            'value_c' => (string) $transaction->user_id,
            'value_d' => $transaction->order_id,
        ];
    }

    protected function generateOrderId(): string
    {
        return Str::upper('DST-'.now()->format('YmdHis').'-'.Str::random(6));
    }

    protected function isMockMode(): bool
    {
        return (bool) config('services.sslcommerz.mock_mode', false);
    }

    protected function markAsPaidMock(DonationTransaction $transaction, array $payload): DonationTransaction
    {
        if ($transaction->status === 'paid') {
            return $transaction->refresh();
        }

        DB::transaction(function () use ($transaction, $payload) {
            $transaction->update([
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

            $post = $transaction->donationPost()->lockForUpdate()->first();
            $newRaisedAmount = (float) $post->raised_amount + (float) $transaction->amount;

            $post->update([
                'raised_amount' => $newRaisedAmount,
                'status' => $newRaisedAmount >= (float) $post->goal_amount ? 'completed' : 'active',
            ]);
        });

        return $transaction->refresh();
    }
}
