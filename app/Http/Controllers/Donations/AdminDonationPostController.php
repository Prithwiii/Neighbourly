<?php

namespace App\Http\Controllers\Donations;

use App\Http\Controllers\Controller;
use App\Models\DonationPost;
use App\Services\Donations\DonationPostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDonationPostController extends Controller
{
    public function __construct(private readonly DonationPostService $donationPostService)
    {
    }

    public function index(): View
    {
        $pendingPosts = DonationPost::with('user')
            ->where('approval_status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.donation-posts.index', compact('pendingPosts'));
    }

    public function approve(Request $request, DonationPost $donationPost): RedirectResponse
    {
        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->donationPostService->approve($donationPost, (int) $request->user()->id, $validated['admin_note'] ?? null);

        return back()->with('success', 'Donation request approved and now visible to the community.');
    }

    public function reject(Request $request, DonationPost $donationPost): RedirectResponse
    {
        $validated = $request->validate([
            'admin_note' => ['required', 'string', 'max:1000'],
        ]);

        $this->donationPostService->reject($donationPost, (int) $request->user()->id, $validated['admin_note']);

        return back()->with('info', 'Donation request rejected.');
    }
}
