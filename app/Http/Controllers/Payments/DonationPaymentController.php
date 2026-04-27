<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\StoreDonationPaymentRequest;
use App\Services\Payments\DonationPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonationPaymentController extends Controller
{
    public function __construct(private readonly DonationPaymentService $donationPaymentService)
    {
    }

    public function create(): View
    {
        return view('payments.donate');
    }

    public function store(StoreDonationPaymentRequest $request): RedirectResponse
    {
        $result = $this->donationPaymentService->initiate($request->validated(), $request->user());

        if (! $result['success']) {
            return back()
                ->withInput()
                ->withErrors(['payment' => $result['message'] ?? 'Unable to start the donation checkout.']);
        }

        if (($result['redirect_mode'] ?? 'external') === 'internal') {
            return redirect()->to($result['gateway_url']);
        }

        return redirect()->away($result['gateway_url']);
    }

    public function success(Request $request): RedirectResponse
    {
        $payment = $this->donationPaymentService->finalizeFromCallback(
            (string) $request->string('tran_id'),
            'success',
            $request->all()
        );

        return redirect()
            ->route('payments.donations.show', $payment->order_id)
            ->with('status', 'Donation payment completed successfully.');
    }

    public function fail(Request $request): RedirectResponse
    {
        $payment = $this->donationPaymentService->finalizeFromCallback(
            (string) $request->string('tran_id'),
            'fail',
            $request->all()
        );

        return redirect()
            ->route('payments.donations.show', $payment->order_id)
            ->with('status', 'Donation payment failed or was declined by the gateway.');
    }

    public function cancel(Request $request): RedirectResponse
    {
        $payment = $this->donationPaymentService->finalizeFromCallback(
            (string) $request->string('tran_id'),
            'cancel',
            $request->all()
        );

        return redirect()
            ->route('payments.donations.show', $payment->order_id)
            ->with('status', 'Donation payment was cancelled.');
    }

    public function ipn(Request $request): JsonResponse
    {
        $payment = $this->donationPaymentService->finalizeFromCallback(
            (string) $request->string('tran_id'),
            'ipn',
            $request->all()
        );

        return response()->json([
            'message' => 'IPN processed successfully.',
            'order_id' => $payment->order_id,
            'status' => $payment->status,
        ]);
    }

    public function show(string $orderId): View
    {
        $payment = $this->donationPaymentService->findByOrderIdOrFail($orderId);

        return view('payments.status', compact('payment'));
    }
}