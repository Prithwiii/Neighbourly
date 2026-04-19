<?php

namespace App\Http\Controllers;

use App\Models\EmergencyAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminEmergencyAlertController extends Controller
{
    /**
     * Pending alert approval queue for admins.
     */
    public function index()
    {
        $pendingCount = EmergencyAlert::where('status', 'pending')->count();

        EmergencyAlert::where('status', 'pending')
            ->whereNull('admin_seen_at')
            ->update(['admin_seen_at' => now()]);

        $pendingAlerts = EmergencyAlert::with('user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.emergency-alerts.index', compact('pendingAlerts', 'pendingCount'));
    }

    public function approve(Request $request, EmergencyAlert $emergencyAlert)
    {
        $validated = $request->validate([
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $emergencyAlert->update([
            'status' => 'approved',
            'admin_note' => $validated['admin_note'] ?? null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_seen_at' => now(),
        ]);

        return back()->with('success', 'Alert approved and now visible to all users.');
    }

    public function reject(Request $request, EmergencyAlert $emergencyAlert)
    {
        $validated = $request->validate([
            'admin_note' => 'required|string|max:1000',
        ]);

        $emergencyAlert->update([
            'status' => 'rejected',
            'admin_note' => $validated['admin_note'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_seen_at' => now(),
        ]);

        return back()->with('info', 'Alert rejected.');
    }
}
