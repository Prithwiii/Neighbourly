<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MarketplaceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $messages = Message::with(['sender', 'receiver', 'marketplaceItem'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->get();

        return view('messages.index', compact('messages'));
    }

    public function marketplace()
    {
        $user = Auth::user();

        $messages = Message::with(['sender', 'receiver', 'marketplaceItem'])
            ->whereNotNull('marketplace_item_id')
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->get();

        return view('messages.marketplace', compact('messages'));
    }

    public function create(MarketplaceItem $marketplace)
    {
        if ($marketplace->user_id === Auth::id()) {
            abort(403);
        }

        return view('messages.create', compact('marketplace'));
    }

    public function store(Request $request, MarketplaceItem $marketplace)
    {
        if ($marketplace->user_id === Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $marketplace->user_id,
            'marketplace_item_id' => $marketplace->id,
            'content' => $validated['content'],
        ]);

        return redirect()->route('messages.marketplace')->with('success', 'Your message has been sent to the seller.');
    }
}
