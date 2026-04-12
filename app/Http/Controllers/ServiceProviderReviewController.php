<?php

namespace App\Http\Controllers;

use App\Models\ServiceProvider;
use App\Models\ServiceProviderReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceProviderReviewController extends Controller
{
    public function store(Request $request, ServiceProvider $serviceProvider)
    {
        if (!$serviceProvider->isVerified()) {
            abort(404);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        ServiceProviderReview::updateOrCreate(
            [
                'service_provider_id' => $serviceProvider->id,
                'user_id' => Auth::id(),
            ],
            [
                'rating' => $validated['rating'],
                'review' => $validated['review'] ?? null,
            ]
        );

        return back()->with('success', 'Thanks! Your review has been saved.');
    }
}
