<?php

namespace App\Http\Controllers;

use App\Models\CheckInRequest;
use App\Models\CheckInMessage;
use Illuminate\Http\Request;

class CheckInMessageController extends Controller
{
    public function store(CheckInRequest $checkIn, Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = $request->user();
        $assignment = $checkIn->activeAssignment()->with('volunteer')->first();

        if (!$assignment) {
            return back()->with('error', 'No active assignment for this check-in.');
        }

        $receiver = $user->id === $checkIn->requester->id
            ? $assignment->volunteer
            : $checkIn->requester;

        CheckInMessage::create([
            'check_in_request_id' => $checkIn->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Message sent!');
    }

    public function getMessages(CheckInRequest $checkIn, Request $request)
    {
        $messages = $checkIn->messages()->with(['sender', 'receiver'])->latest()->get();

        return response()->json($messages);
    }
}
