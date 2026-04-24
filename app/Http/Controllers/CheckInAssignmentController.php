<?php

namespace App\Http\Controllers;

use App\Models\CheckInRequest;
use App\Models\CheckInAssignment;
use Illuminate\Http\Request;

class CheckInAssignmentController extends Controller
{
    public function accept(CheckInRequest $checkIn, Request $request)
    {
        $volunteer = $request->user();

        $existing = CheckInAssignment::where('check_in_request_id', $checkIn->id)
            ->where('volunteer_id', $volunteer->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already accepted this request.');
        }

        $assignment = CheckInAssignment::create([
            'check_in_request_id' => $checkIn->id,
            'volunteer_id' => $volunteer->id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'You have accepted this check-in request. Waiting for requester confirmation.');
    }

    public function confirm(CheckInAssignment $assignment, Request $request)
    {
        $this->authorize('confirm', $assignment);

        $assignment->confirm();

        return back()->with('success', 'Volunteer confirmed! Check-in in progress.');
    }

    public function complete(CheckInAssignment $assignment, Request $request)
    {
        $assignment->complete();

        return redirect()->route('check-ins.rate', $assignment)->with('success', 'Check-in completed. Please rate the volunteer.');
    }

    public function cancel(CheckInAssignment $assignment, Request $request)
    {
        $this->authorize('cancel', $assignment);

        $assignment->update(['status' => 'cancelled']);

        if ($assignment->checkInRequest->assignments()->where('status', '!=', 'cancelled')->count() === 0) {
            $assignment->checkInRequest->update(['status' => 'open']);
        }

        return back()->with('success', 'Assignment cancelled.');
    }
}
