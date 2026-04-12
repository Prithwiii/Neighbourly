@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div style="max-width: 700px; margin: 0 auto; padding: 20px;">
    <div style="margin-bottom: 30px;">
        <h1 style="margin: 0;">Conversation</h1>
        <p style="margin: 8px 0 0; color: #6b7280;">Chat directly with {{ $user->name }}.</p>
    </div>

    <div style="background-color: #fff; border-radius: 12px; padding: 24px; border: 1px solid #e5e7eb; box-shadow: 0 1px 4px rgba(0,0,0,0.05); margin-bottom: 24px;">
        <h2 style="margin: 0 0 10px; color: #111827;">{{ $providerProfile ? $providerProfile->full_name : $user->name }}</h2>
        @if($providerProfile)
            <p style="margin: 0 0 8px; color: #4b5563;">{{ $providerProfile->service_category }} | {{ $providerProfile->location }}</p>
            <p style="margin: 0; color: #4b5563;">{{ $providerProfile->description }}</p>
        @else
            <p style="margin: 0; color: #4b5563;">Direct user conversation.</p>
        @endif
    </div>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #0f5132; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if($messages->isNotEmpty())
        <div style="margin-bottom: 24px;">
            <div style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 16px;">Conversation</div>
            <div style="display: grid; gap: 14px;">
                @foreach($messages as $message)
                    <div style="background-color: {{ $message->sender_id === auth()->id() ? '#eef2ff' : '#f8fafc' }}; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px;">
                        <div style="display: flex; justify-content: space-between; gap: 12px; align-items: center; margin-bottom: 8px; font-size: 14px; color: #4b5563;">
                            <span><strong>{{ $message->sender->id === auth()->id() ? 'You' : $message->sender->name }}</strong></span>
                            <span>{{ $message->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div style="color: #111827; margin-bottom: 12px; white-space: pre-line;">{{ $message->content }}</div>
                        @if($message->attachment_path)
                            @if(str_starts_with($message->attachment_type, 'image/'))
                                <img src="{{ Storage::url($message->attachment_path) }}" alt="Attachment" style="max-width: 100%; border-radius: 10px;">
                            @elseif(str_starts_with($message->attachment_type, 'video/'))
                                <video controls style="width: 100%; border-radius: 10px;">
                                    <source src="{{ Storage::url($message->attachment_path) }}" type="{{ $message->attachment_type }}">
                                    Your browser does not support the video tag.
                                </video>
                            @else
                                <a href="{{ Storage::url($message->attachment_path) }}" target="_blank" style="color: #2563eb; text-decoration: none;">Download attachment</a>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($showReviewPrompt && $providerProfile)
        <div id="reviewPrompt" style="position: relative; background-color: #fff7ed; border: 1px solid #fdba74; border-radius: 14px; padding: 20px; margin-bottom: 24px; box-shadow: 0 10px 24px rgba(0,0,0,0.08);">
            <div style="display:flex; justify-content:space-between; gap: 12px; align-items:flex-start; margin-bottom: 12px; flex-wrap: wrap;">
                <div>
                    <h3 style="margin: 0 0 6px; color: #9a3412;">Rate this provider</h3>
                    <p style="margin: 0; color: #7c2d12;">You have exchanged 10 messages. Leave a quick review or ignore for now.</p>
                </div>
                <button type="button" id="dismissReviewPrompt" style="background: transparent; border: 0; font-size: 20px; line-height: 1; cursor: pointer; color: #9a3412;">&times;</button>
            </div>

            <form method="POST" action="{{ route('services.reviews.store', $providerProfile) }}" style="display:grid; gap:12px;">
                @csrf
                <div>
                    <label for="review_rating" style="display:block; font-weight:700; margin-bottom:8px; color:#111827;">Rating</label>
                    <select id="review_rating" name="rating" required style="padding:8px; width:140px; border:1px solid #d1d5db; border-radius:8px;">
                        <option value="">Select</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} Star</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="review_text" style="display:block; font-weight:700; margin-bottom:8px; color:#111827;">Review</label>
                    <textarea id="review_text" name="review" rows="3" placeholder="Share your experience, or leave it blank..." style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:8px;">{{ old('review') }}</textarea>
                </div>

                <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
                    <button type="submit" style="background-color:#f97316; color:white; padding:10px 18px; border:none; border-radius:8px; cursor:pointer; font-weight:700;">Submit Review</button>
                    <button type="button" id="ignoreReviewPrompt" style="background-color:transparent; color:#7c2d12; padding:10px 0; border:none; cursor:pointer; font-weight:700;">Ignore</button>
                </div>
            </form>
        </div>
    @endif

    <form method="POST" action="{{ route('messages.direct.store', $user) }}" enctype="multipart/form-data" style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px;">
        @csrf

        <div style="margin-bottom: 20px;">
            <label for="content" style="display: block; font-weight: 700; margin-bottom: 8px; color: #111827;">Message</label>
            <textarea id="content" name="content" rows="6" style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 16px; color: #111827;">{{ old('content') }}</textarea>
            @error('content')
                <p style="margin-top: 8px; color: #dc2626; font-size: 14px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="attachment" style="display: block; font-weight: 700; margin-bottom: 8px; color: #111827;">Photo or video (optional)</label>
            <input type="file" id="attachment" name="attachment" accept="image/*,video/*" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 8px;">
            @error('attachment')
                <p style="margin-top: 8px; color: #dc2626; font-size: 14px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
            <button type="submit" style="background-color: #0d6efd; color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 700;">Send Message</button>
            <a href="{{ route('messages.index') }}" style="text-decoration: none; color: #374151;">← Back to messages</a>
        </div>
    </form>
</div>

@if($showReviewPrompt && $providerProfile)
    <script>
        (function () {
            const prompt = document.getElementById('reviewPrompt');
            const dismissButton = document.getElementById('dismissReviewPrompt');
            const ignoreButton = document.getElementById('ignoreReviewPrompt');
            const storageKey = 'provider_review_prompt_dismissed_{{ $providerProfile->id }}_{{ auth()->id() }}';

            if (localStorage.getItem(storageKey) === '1') {
                prompt?.remove();
                return;
            }

            const dismiss = function () {
                localStorage.setItem(storageKey, '1');
                prompt?.remove();
            };

            dismissButton?.addEventListener('click', dismiss);
            ignoreButton?.addEventListener('click', dismiss);
        })();
    </script>
@endif
@endsection
