<?php

namespace App\Http\Controllers\Donations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Donations\StoreDonationPostRequest;
use App\Models\DonationPost;
use App\Services\Donations\DonationPostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DonationPostController extends Controller
{
    public function __construct(private readonly DonationPostService $donationPostService)
    {
    }

    public function index(): View
    {
        $posts = DonationPost::with('user')
            ->where('approval_status', 'approved')
            ->latest()
            ->paginate(12);

        $pendingDonationCount = auth()->user()->isAdmin()
            ? DonationPost::where('approval_status', 'pending')->count()
            : 0;

        return view('donation-posts.index', compact('posts', 'pendingDonationCount'));
    }

    public function create(): View
    {
        return view('donation-posts.create');
    }

    public function store(StoreDonationPostRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $proofFiles = [];

        foreach ($request->file('proof_files', []) as $file) {
            $proofFiles[] = [
                'path' => $file->store('donation-proofs', 'public'),
                'name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
            ];
        }

        $validated['proof_files'] = $proofFiles ?: null;

        $post = $this->donationPostService->create($validated, $request->user());

        return redirect()
            ->route('donation-posts.show', $post)
            ->with('success', 'Donation request submitted and is now pending admin approval.');
    }

    public function show(DonationPost $donationPost): View
    {
        $user = auth()->user();
        $canView = $donationPost->approval_status === 'approved'
            || $user->id === $donationPost->user_id
            || $user->isAdmin();

        if (! $canView) {
            abort(404);
        }

        $donationPost->load(['user', 'reviewer']);

        $recentDonations = $donationPost->donations()
            ->where('status', 'paid')
            ->latest('paid_at')
            ->take(5)
            ->get();

        return view('donation-posts.show', compact('donationPost', 'recentDonations'));
    }

    public function downloadProof(DonationPost $donationPost, int $index)
    {
        $user = auth()->user();
        $canView = $user->isAdmin() || $user->id === $donationPost->user_id;

        if (! $canView) {
            abort(403);
        }

        $files = $donationPost->proof_files ?? [];
        $file = $files[$index] ?? null;

        if (! $file || ! isset($file['path'])) {
            abort(404);
        }

        return Storage::disk('public')->download($file['path'], $file['name'] ?? basename($file['path']));
    }
}
