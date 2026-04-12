@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div style="max-width: 700px; margin: 0 auto; padding: 20px;">
    <div style="margin-bottom: 30px;">
        <h1 style="margin: 0;">Send Message</h1>
        <p style="margin: 8px 0 0; color: #6b7280;">Send a message to the seller about this marketplace listing.</p>
    </div>

    <div style="background-color: #fff; border-radius: 12px; padding: 24px; border: 1px solid #e5e7eb; box-shadow: 0 1px 4px rgba(0,0,0,0.05); margin-bottom: 24px;">
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 220px;">
                @if($marketplace->image)
                    <img src="{{ Storage::url($marketplace->image) }}" alt="Marketplace item image" style="width: 100%; border-radius: 8px; object-fit: cover;">
                @else
                    <div style="width: 100%; aspect-ratio: 4 / 3; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; border-radius: 8px; color: #6b7280;">
                        📷 No Image
                    </div>
                @endif
            </div>
            <div style="flex: 2; min-width: 220px;">
                <h2 style="margin: 0 0 10px; color: #111827;">{{ $marketplace->title }}</h2>
                <p style="margin: 0 0 12px; color: #4b5563; line-height: 1.6;">{{ $marketplace->description }}</p>
                <div style="font-weight: 700; color: #16a34a; font-size: 20px;">{{ number_format($marketplace->price, 2) }} tk</div>
                <div style="margin-top: 12px; color: #4b5563;">Seller: {{ $marketplace->user->name }}</div>
            </div>
        </div>
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

    <form method="POST" action="{{ route('messages.store', $marketplace) }}" enctype="multipart/form-data" style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px;">
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
            <a href="{{ route('marketplace.show', $marketplace) }}" style="text-decoration: none; color: #374151;">← Back to listing</a>
        </div>
    </form>
</div>
@endsection
