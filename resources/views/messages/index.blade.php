@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
        <div>
            <h1 style="margin: 0;">Messages</h1>
            <p style="margin: 5px 0 0; color: #6b7280;">All messages for your account.</p>
        </div>
        <a href="{{ route('messages.marketplace') }}" style="background-color: #0d6efd; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none;">Marketplace Messages</a>
    </div>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #0f5132; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if($messages->isEmpty())
        <div style="background-color: #f8fafc; color: #475569; padding: 40px; border-radius: 10px; text-align: center; border: 1px solid #e2e8f0;">
            <h2 style="margin: 0 0 10px;">No messages yet</h2>
            <p style="margin: 0;">Send a message from a marketplace listing to start a conversation.</p>
        </div>
    @else
        <div style="display: grid; gap: 16px;">
            @foreach($messages as $message)
                <div style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
                        <div style="font-weight: 700; color: #111827;">
                            {{ $message->marketplaceItem ? $message->marketplaceItem->title : 'General Message' }}
                        </div>
                        <div style="font-size: 13px; color: #6b7280;">
                            {{ $message->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div style="color: #374151; margin-bottom: 12px;">
                        {{ $message->content }}
                    </div>
                    <div style="font-size: 14px; color: #4b5563; display: flex; flex-wrap: wrap; gap: 12px;">
                        <span><strong>From:</strong> {{ $message->sender->name }}</span>
                        <span><strong>To:</strong> {{ $message->receiver->name }}</span>
                        @if($message->marketplaceItem)
                            <span><strong>Listing:</strong> <a href="{{ route('marketplace.show', $message->marketplaceItem) }}" style="color: #2563eb; text-decoration: none;">View item</a></span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
