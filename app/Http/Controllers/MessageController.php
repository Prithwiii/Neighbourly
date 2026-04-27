<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MarketplaceItem;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $threads = Message::with(['sender.serviceProviderProfile', 'receiver.serviceProviderProfile', 'marketplaceItem'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(function (Message $message) use ($user) {
                if ($message->marketplace_item_id) {
                    return 'marketplace:'.$message->marketplace_item_id;
                }

                $counterpartId = $message->sender_id === $user->id
                    ? $message->receiver_id
                    : $message->sender_id;

                return 'direct:'.$counterpartId;
            })
            ->map(function ($group) use ($user) {
                $thread = $group->first();

                if ($thread->marketplaceItem) {
                    $thread->thread_title = $thread->marketplaceItem->title;
                    $thread->thread_url = route('messages.create', $thread->marketplaceItem);
                    $thread->thread_type = 'marketplace';

                    return $thread;
                }

                $counterpart = $thread->sender_id === $user->id ? $thread->receiver : $thread->sender;
                $provider = $counterpart?->serviceProviderProfile;

                $thread->thread_title = $provider ? $provider->full_name : $counterpart->name;
                $thread->thread_url = route('messages.direct.create', $counterpart);
                $thread->thread_type = 'direct';

                return $thread;
            })
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

    public function provider(ServiceProvider $serviceProvider)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if ($serviceProvider->user_id === Auth::id()) {
            return redirect()->route('services.index');
        }

        $user = Auth::user();

        $messages = Message::with(['sender', 'receiver'])
            ->whereNull('marketplace_item_id')
            ->where(function ($query) use ($user, $serviceProvider) {
                $query->where(function ($subQuery) use ($user, $serviceProvider) {
                    $subQuery->where('sender_id', $user->id)
                        ->where('receiver_id', $serviceProvider->user_id);
                })->orWhere(function ($subQuery) use ($user, $serviceProvider) {
                    $subQuery->where('sender_id', $serviceProvider->user_id)
                        ->where('receiver_id', $user->id);
                });
            })
            ->orderBy('created_at')
            ->get();

        $messageCount = $messages->count();
        $existingReview = ServiceProviderReview::where('service_provider_id', $serviceProvider->id)
            ->where('user_id', $user->id)
            ->first();

        $showReviewPrompt = $messageCount >= 10 && ! $existingReview;

        Message::whereNull('marketplace_item_id')
            ->where('sender_id', $serviceProvider->user_id)
            ->where('receiver_id', $user->id)
            ->update(['is_read' => true]);

        return view('messages.provider', compact('serviceProvider', 'messages', 'messageCount', 'existingReview', 'showReviewPrompt'));
    }

    public function direct(User $user)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if ($user->id === Auth::id()) {
            return redirect()->route('messages.index');
        }

        $currentUser = Auth::user();

        $messages = Message::with(['sender', 'receiver'])
            ->whereNull('marketplace_item_id')
            ->where(function ($query) use ($currentUser, $user) {
                $query->where(function ($subQuery) use ($currentUser, $user) {
                    $subQuery->where('sender_id', $currentUser->id)
                        ->where('receiver_id', $user->id);
                })->orWhere(function ($subQuery) use ($currentUser, $user) {
                    $subQuery->where('sender_id', $user->id)
                        ->where('receiver_id', $currentUser->id);
                });
            })
            ->orderBy('created_at')
            ->get();

        Message::whereNull('marketplace_item_id')
            ->where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->update(['is_read' => true]);

        $providerProfile = $user->serviceProviderProfile;
        $messageCount = $messages->count();
        $existingReview = null;
        $showReviewPrompt = false;

        if ($providerProfile && $providerProfile->isVerified() && $providerProfile->user_id !== $currentUser->id) {
            $existingReview = ServiceProviderReview::where('service_provider_id', $providerProfile->id)
                ->where('user_id', $currentUser->id)
                ->first();

            $showReviewPrompt = $messageCount >= 10 && ! $existingReview;
        }

        return view('messages.direct', compact('user', 'providerProfile', 'messages', 'messageCount', 'existingReview', 'showReviewPrompt'));
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

    public function storeProvider(Request $request, ServiceProvider $serviceProvider)
    {
        $user = Auth::user();

        if ($serviceProvider->user_id === $user->id) {
            abort(403, 'You cannot message your own provider profile.');
        }

        $validated = $request->validate([
            'content' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv|max:20480',
        ]);

        if (empty($validated['content']) && ! $request->hasFile('attachment')) {
            return back()->withErrors(['content' => 'Please enter a message or attach a photo/video.'])->withInput();
        }

        $messageData = [
            'sender_id' => $user->id,
            'receiver_id' => $serviceProvider->user_id,
            'marketplace_item_id' => null,
            'content' => $validated['content'] ?? '',
            'is_read' => false,
        ];

        if ($request->hasFile('attachment')) {
            $messageData['attachment_path'] = $request->file('attachment')->store('messages', 'public');
            $messageData['attachment_type'] = $request->file('attachment')->getClientMimeType();
        }

        Message::create($messageData);

        return redirect()->route('messages.provider.create', $serviceProvider)->with('success', 'Your message has been sent.');
    }

    public function storeDirect(Request $request, User $user)
    {
        $sender = Auth::user();

        if ($user->id === $sender->id) {
            abort(403, 'You cannot message yourself.');
        }

        $validated = $request->validate([
            'content' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv|max:20480',
        ]);

        if (empty($validated['content']) && ! $request->hasFile('attachment')) {
            return back()->withErrors(['content' => 'Please enter a message or attach a photo/video.'])->withInput();
        }

        $messageData = [
            'sender_id' => $sender->id,
            'receiver_id' => $user->id,
            'marketplace_item_id' => null,
            'content' => $validated['content'] ?? '',
            'is_read' => false,
        ];

        if ($request->hasFile('attachment')) {
            $messageData['attachment_path'] = $request->file('attachment')->store('messages', 'public');
            $messageData['attachment_type'] = $request->file('attachment')->getClientMimeType();
        }

        Message::create($messageData);

        return redirect()->route('messages.direct.create', $user)->with('success', 'Your message has been sent.');
    }
}
