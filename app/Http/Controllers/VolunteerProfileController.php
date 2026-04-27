<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VolunteerProfile;
use Illuminate\Http\Request;

class VolunteerProfileController extends Controller
{
    public function show(User $volunteer)
    {
        $volunteer->load('volunteerProfile');

        $profile = $volunteer->volunteerProfile ?? VolunteerProfile::create(['user_id' => $volunteer->id]);

        $reviews = $volunteer->checkInAssignments()
            ->where('status', 'completed')
            ->with('rating.reviewer')
            ->get()
            ->map(fn($assignment) => $assignment->rating)
            ->filter()
            ->values();

        return view('volunteers.profile', compact('volunteer', 'profile', 'reviews'));
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        $profile = $user->volunteerProfile ?? VolunteerProfile::create(['user_id' => $user->id]);

        return view('volunteers.edit-profile', compact('profile'));
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $profile = $user->volunteerProfile ?? VolunteerProfile::create(['user_id' => $user->id]);

        $validated = $request->validate([
            'bio' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:255',
            'verified_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('verified_id')) {
            $path = $request->file('verified_id')->store('verified_ids', 'public');
            $validated['verified_id_path'] = $path;
        }

        $profile->update($validated);

        return redirect()->route('volunteers.profile', $user)->with('success', 'Profile updated!');
    }
}

