<?php

namespace App\Http\Controllers;

use App\Models\ServiceProvider;
use Illuminate\Http\Request;

class AdminServiceProviderController extends Controller
{
    /**
     * Admin queue for provider verification.
     */
    public function index()
    {
        ServiceProvider::whereIn('verification_status', ['pending', 'flagged'])
            ->whereNull('admin_seen_at')
            ->update(['admin_seen_at' => now()]);

        $pendingProviders = ServiceProvider::with('user')
            ->whereIn('verification_status', ['pending', 'flagged'])
            ->latest()
            ->paginate(20);

        return view('admin.providers.index', compact('pendingProviders'));
    }

    public function approve(ServiceProvider $serviceProvider)
    {
        $serviceProvider->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
            'admin_note' => null,
        ]);

        return back()->with('success', 'Provider approved and now visible publicly.');
    }

    public function reject(Request $request, ServiceProvider $serviceProvider)
    {
        $validated = $request->validate([
            'admin_note' => 'required|string|max:500',
        ]);

        $serviceProvider->update([
            'verification_status' => 'rejected',
            'admin_note' => $validated['admin_note'],
        ]);

        return back()->with('info', 'Provider rejected.');
    }
}
