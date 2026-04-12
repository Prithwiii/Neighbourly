<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MarketplaceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $threads = Message::with(['sender', 'receiver', 'marketplaceItem'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->whereNotNull('marketplace_item_id')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('marketplace_item_id')
            ->map(fn($group) => $group->first())
            ->values();

        return view('messages.index', compact('threads'));
    }

    public function marketplace()
    {
        $user = Auth::user();

        $threads = Message::with(['sender', 'receiver', 'marketplaceItem'])
            ->whereNotNull('marketplace_item_id')
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('marketplace_item_id')
            ->map(fn($group) => $group->first())
            ->values();

        return view('messages.marketplace', compact('threads'));
    }

    public function create(MarketplaceItem $marketplace)
    {
        $user = Auth::user();

        $messages = Message::with(['sender', 'receiver'])
            ->where('marketplace_item_id', $marketplace->id)
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->orderBy('created_at')
            ->get();

        if ($marketplace->user_id === $user->id && $messages->isEmpty()) {
            return view('messages.create', compact('marketplace', 'messages'));
        }

        if ($marketplace->user_id !== $user->id && $messages->isEmpty()) {
            return view('messages.create', compact('marketplace', 'messages'));
        }

        if ($marketplace->user_id === $user->id) {
            Message::where('marketplace_item_id', $marketplace->id)
                ->where('receiver_id', $user->id)
                ->update(['is_read' => true]);
        }

        return view('messages.create', compact('marketplace', 'messages'));
    }

    public function store(Request $request, MarketplaceItem $marketplace)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'content' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv|max:20480',
        ]);

        if (empty($validated['content']) && ! $request->hasFile('attachment')) {
            return back()->withErrors(['content' => 'Please enter a message or attach a photo/video.'])->withInput();
        }

        $receiverId = null;

        if ($marketplace->user_id === $user->id) {
            $latestBuyer = Message::where('marketplace_item_id', $marketplace->id)
                ->where('sender_id', '!=', $user->id)
                ->latest('created_at')
                ->first();

            if (! $latestBuyer) {
                abort(403, 'No buyer conversation exists yet.');
            }

            $receiverId = $latestBuyer->sender_id;
        } else {
            $receiverId = $marketplace->user_id;
        }

        $messageData = [
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'marketplace_item_id' => $marketplace->id,
            'content' => $validated['content'] ?? '',
            'is_read' => false,
        ];

        if ($request->hasFile('attachment')) {
            $messageData['attachment_path'] = $request->file('attachment')->store('messages', 'public');
            $messageData['attachment_type'] = $request->file('attachment')->getClientMimeType();
        }

        Message::create($messageData);

        return redirect()->route('messages.create', $marketplace)->with('success', 'Your message has been sent.');
    }
}
