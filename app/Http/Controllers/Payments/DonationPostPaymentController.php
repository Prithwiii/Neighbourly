<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\StoreDonationTransactionRequest;
use App\Models\DonationPost;
use App\Services\Payments\DonationPostPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonationPostPaymentController extends Controller
{
    public function __construct(private readonly DonationPostPaymentService $paymentService)
    {
    }

    public function donate(StoreDonationTransactionRequest $request, DonationPost $donationPost): RedirectResponse
    {
        $payload = array_merge($request->validated(), [
            'donor_name' => $request->input('donor_name', $request->user()->name ?? null),
            'donor_email' => $request->input('donor_email', $request->user()->email ?? null),
        ]);

        $result = $this->paymentService->initiate($donationPost, $payload, $request->user());

        if (! $result['success']) {
            return back()
                ->withInput()
                ->withErrors(['payment' => $result['message'] ?? 'Unable to start donation payment.']);
        }

        if (($result['redirect_mode'] ?? 'external') === 'internal') {
            return redirect()->to($result['gateway_url']);
        }

        return redirect()->away($result['gateway_url']);
    }

    public function success(Request $request): RedirectResponse
    {
        $transaction = $this->paymentService->finalizeFromCallback(
            (string) $request->string('tran_id'),
            'success',
            $request->all()
        );

        return redirect()
            ->route('payments.posts.receipt', $transaction->order_id)
            ->with('status', 'Thank you! Your donation has been received successfully.');
    }

    public function fail(Request $request): RedirectResponse
    {
        $transaction = $this->paymentService->finalizeFromCallback(
            (string) $request->string('tran_id'),
            'fail',
            $request->all()
        );

        return redirect()
            ->route('payments.posts.receipt', $transaction->order_id)
            ->with('status', 'Payment failed. Please try again.');
    }

    public function cancel(Request $request): RedirectResponse
    {
        $transaction = $this->paymentService->finalizeFromCallback(
            (string) $request->string('tran_id'),
            'cancel',
            $request->all()
        );

        return redirect()
            ->route('payments.posts.receipt', $transaction->order_id)
            ->with('status', 'Payment was canceled.');
    }

    public function ipn(Request $request): JsonResponse
    {
        $transaction = $this->paymentService->finalizeFromCallback(
            (string) $request->string('tran_id'),
            'ipn',
            $request->all()
        );

        return response()->json([
            'message' => 'IPN processed successfully.',
            'order_id' => $transaction->order_id,
            'status' => $transaction->status,
        ]);
    }

    public function receipt(string $orderId): View
    {
        $transaction = $this->paymentService->findByOrderIdOrFail($orderId);

        return view('donation-posts.receipt', compact('transaction'));
    }
}
