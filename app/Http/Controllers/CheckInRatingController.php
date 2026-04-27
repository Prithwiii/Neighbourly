<?php

namespace App\Http\Controllers;

use App\Models\CheckInAssignment;
use App\Models\CheckInRating;
use Illuminate\Http\Request;

class CheckInRatingController extends Controller
{
    public function create(CheckInAssignment $assignment)
    {
        $this->authorize('create', [CheckInRating::class, $assignment]);

        if ($assignment->rating) {
            return redirect()->route('check-ins.show', $assignment->checkInRequest)
                ->with('info', 'You have already rated this check-in.');
        }

        return view('check-ins.rate', compact('assignment'));
    }

    public function store(CheckInAssignment $assignment, Request $request)
    {
        $this->authorize('create', [CheckInRating::class, $assignment]);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        $validated['check_in_assignment_id'] = $assignment->id;
        $validated['reviewer_id'] = $request->user()->id;

        CheckInRating::create($validated);

        $assignment->volunteer->volunteerProfile?->updateStats();

        return redirect()->route('check-ins.show', $assignment->checkInRequest)
            ->with('success', 'Thank you for your rating!');
    }
}
