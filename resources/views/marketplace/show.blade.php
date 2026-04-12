@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="margin: 0;">{{ $marketplace->title }}</h1>
        @if($marketplace->user_id === auth()->id())
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('marketplace.edit', $marketplace) }}" style="background-color: #007bff; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none;">✏️ Edit</a>
                <form method="POST" action="{{ route('marketplace.destroy', $marketplace) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this listing?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer;">🗑️ Delete</button>
                </form>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 12px 20px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background-color: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div style="display: flex; gap: 30px; flex-wrap: wrap;">
            <!-- Image -->
            <div style="flex: 1; min-width: 300px;">
                @if($marketplace->image)
                    <img src="{{ Storage::url($marketplace->image) }}" alt="Item photo" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 5px;">
                @else
                    <div style="width: 100%; height: 300px; background-color: #f0f0f0; border-radius: 5px; display: flex; align-items: center; justify-content: center; color: #666; font-size: 18px;">
                        📷 No Image
                    </div>
                @endif
            </div>

            <!-- Details -->
            <div style="flex: 1; min-width: 300px;">
                <div style="font-size: 24px; font-weight: bold; color: #28a745; margin-bottom: 20px;">
                    {{ number_format($marketplace->price, 2) }} tk
                </div>

                <div style="margin-bottom: 20px;">
                    <h3 style="margin: 0 0 10px 0; color: #333;">Description</h3>
                    <p style="color: #555; line-height: 1.6; margin: 0;">
                        {{ $marketplace->description }}
                    </p>
                </div>

                <div style="border-top: 1px solid #eee; padding-top: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <strong>👤 Seller:</strong>
                        <span>{{ $marketplace->user->name }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; color: #666; font-size: 14px;">
                        <span>🕒 Listed {{ $marketplace->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                @if($marketplace->user_id !== auth()->id())
                    <div style="margin-top: 30px;">
                        <button style="background-color: #28a745; color: white; padding: 12px 30px; border: none; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; width: 100%;">
                            💬 Send Message
                        </button>
                        <small style="color: #999; display: block; text-align: center; margin-top: 10px;">
                            Messaging feature coming soon
                        </small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('marketplace.index') }}" style="background-color: #6c757d; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">← Back to Marketplace</a>
    </div>
</div>
@endsection