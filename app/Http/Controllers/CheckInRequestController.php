<?php

namespace App\Http\Controllers;

use App\Models\CheckInRequest;
use App\Models\CheckInAssignment;
use Illuminate\Http\Request;

class CheckInRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userLat = $user?->lat;
        $userLng = $user?->lng;

        $requests = CheckInRequest::with(['requester', 'assignments.volunteer', 'activeAssignment.volunteer'])
            ->where('status', 'open')
            ->orderByRaw("
                CASE
                    WHEN urgency_level = 'high' THEN 1
                    WHEN urgency_level = 'medium' THEN 2
                    WHEN urgency_level = 'low' THEN 3
                END
            ");

        if ($userLat && $userLng) {
            $requests = $requests->get()
                ->sortBy(function ($req) use ($userLat, $userLng) {
                    return $req->distanceFrom($userLat, $userLng);
                })
                ->values();
        } else {
            $requests = $requests->orderByNewest()->get();
        }

        return view('check-ins.hub', compact('requests'));
    }

    public function create()
    {
        return view('check-ins.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'work_type' => 'required|in:elderly_care,childcare,home_help,companionship,medical_support,other',
            'urgency_level' => 'required|in:low,medium,high',
            'phone_number' => 'required|string|max:20',
            'preferred_date' => 'nullable|date|after:today',
        ]);

        $user = $request->user();
        $validated['user_id'] = $user->id;
        $validated['latitude'] = $user->lat;
        $validated['longitude'] = $user->lng;
        $validated['location_name'] = 'Your location';

        $checkIn = CheckInRequest::create($validated);

        return redirect()->route('check-ins.show', $checkIn)
            ->with('success', 'Check-in request created successfully!');
    }

    public function show(CheckInRequest $checkIn)
    {
        $checkIn->load([
            'requester',
            'assignments.volunteer.volunteerProfile',
            'activeAssignment.volunteer.volunteerProfile',
            'messages.sender'
        ]);

        $activeAssignment = $checkIn->activeAssignment;

        return view('check-ins.show', compact('checkIn', 'activeAssignment'));
    }

    public function updateStatus(CheckInRequest $checkIn, Request $request)
    {
        $this->authorize('update', $checkIn);

        $validated = $request->validate([
            'status' => 'required|in:open,assigned,in_progress,completed,cancelled',
        ]);

        $checkIn->update($validated);

        return back()->with('success', 'Status updated!');
    }
}
