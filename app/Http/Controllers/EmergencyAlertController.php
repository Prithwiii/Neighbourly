<?php

namespace App\Http\Controllers;

use App\Models\EmergencyAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmergencyAlertController extends Controller
{
    /**
     * Show approved alerts to users.
     */
    public function index()
    {
        $alerts = EmergencyAlert::with('user')
            ->where('status', 'approved')
            ->latest()
            ->paginate(12);

        $newAlertRequests = 0;
        if (Auth::user()->isAdmin()) {
            $newAlertRequests = EmergencyAlert::where('status', 'pending')
                ->whereNull('admin_seen_at')
                ->count();
        }

        return view('emergency-alerts.index', compact('alerts', 'newAlertRequests'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $types = [
            'Crime',
            'Lost Individual',
            'Lost Pet',
            'Medical Emergency',
            'Fire',
            'Other',
        ];

        return view('emergency-alerts.create', compact('types'));
    }

    /**
     * Save a new alert (always starts as pending).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'alert_type' => 'required|string|max:60',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:4000',
            'owner_phone' => 'required|string|max:30',
            'location' => 'nullable|string|max:255',
            'is_anonymous' => 'nullable|boolean',
            'media' => 'nullable|array|max:5',
            'media.*' => 'file|mimetypes:image/jpeg,image/png,image/gif,image/webp,video/mp4,video/quicktime,video/webm,video/x-msvideo|max:20480',
        ]);

        $validated['owner_phone'] = $this->normalizeBangladeshPhone($validated['owner_phone']);
        $media = [];

        foreach ($request->file('media', []) as $file) {
            $path = $file->store('emergency-alerts', 'public');

            $media[] = [
                'path' => $path,
                'mime' => $file->getMimeType(),
                'name' => $file->getClientOriginalName(),
            ];
        }

        EmergencyAlert::create([
            'user_id' => Auth::id(),
            'alert_type' => $validated['alert_type'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'media' => $media ?: null,
            'owner_phone' => $validated['owner_phone'],
            'location' => $validated['location'] ?? null,
            'is_anonymous' => (bool) ($validated['is_anonymous'] ?? false),
            'status' => 'pending',
        ]);

        return redirect()->route('emergency-alerts.index')
            ->with('success', 'Emergency alert submitted. It will be visible after admin approval.');
    }

    /**
     * Show one alert.
     */
    public function show(EmergencyAlert $emergencyAlert)
    {
        $user = Auth::user();
        $canViewNonApproved = $user->isAdmin() || $user->id === $emergencyAlert->user_id;

        if (! $emergencyAlert->isApproved() && ! $canViewNonApproved) {
            abort(404);
        }

        $emergencyAlert->load([
            'user',
            'comments.user',
        ]);

        return view('emergency-alerts.show', compact('emergencyAlert'));
    }

    /**
     * Add a public comment to an approved alert.
     */
    public function storeComment(Request $request, EmergencyAlert $emergencyAlert)
    {
        if (! $emergencyAlert->isApproved()) {
            return back()->withErrors(['comment' => 'You can comment only after this alert is approved.']);
        }

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $emergencyAlert->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Comment posted.');
    }

    /**
     * Delete an alert.
     * Owner can delete anytime; admin can delete only approved alerts.
     */
    public function destroy(EmergencyAlert $emergencyAlert)
    {
        $user = Auth::user();

        $canDeleteAsOwner = $user->id === $emergencyAlert->user_id;
        $canDeleteAsAdmin = $user->isAdmin() && $emergencyAlert->isApproved();

        if (! $canDeleteAsOwner && ! $canDeleteAsAdmin) {
            abort(403, 'You are not allowed to delete this alert.');
        }

        foreach ($emergencyAlert->media ?? [] as $attachment) {
            $path = $attachment['path'] ?? null;

            if ($path) {
                Storage::disk('public')->delete($path);
            }
        }

        $emergencyAlert->comments()->delete();
        $emergencyAlert->delete();

        return redirect()->route('emergency-alerts.index')
            ->with('success', 'Emergency alert deleted successfully.');
    }

    private function normalizeBangladeshPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', trim($phone));

        if ($digits === '') {
            return '+880';
        }

        if (str_starts_with($digits, '880')) {
            $digits = substr($digits, 3);
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return '+880'.$digits;
    }
}
