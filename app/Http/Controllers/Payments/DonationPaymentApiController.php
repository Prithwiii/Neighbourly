<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\ApiDonateRequest;
use App\Models\DonationPost;
use App\Services\Payments\DonationPostPaymentService;
use Illuminate\Http\JsonResponse;

class DonationPaymentApiController extends Controller
{
    public function __construct(private readonly DonationPostPaymentService $paymentService)
    {
    }

    public function store(ApiDonateRequest $request): JsonResponse
    {
        $post = DonationPost::findOrFail((int) $request->input('post_id'));

        $payload = [
            'amount' => $request->input('amount'),
            'payment_method' => $request->input('payment_method', 'card'),
            'donor_name' => optional($request->user())->name,
            'donor_email' => optional($request->user())->email,
            'donor_phone' => '',
        ];

        $result = $this->paymentService->initiate($post, $payload, $request->user());

        if (! $result['success']) {
            return response()->json([
                'message' => $result['message'] ?? 'Unable to initialize donation payment.',
            ], 422);
        }

        $transaction = $result['transaction'];

        return response()->json([
            'message' => 'Donation transaction created.',
            'transaction_id' => $transaction->order_id,
            'payment_url' => $result['gateway_url'],
            'success_url' => route('payments.posts.success'),
            'fail_url' => route('payments.posts.fail'),
            'cancel_url' => route('payments.posts.cancel'),
        ], 201);
    }
}
