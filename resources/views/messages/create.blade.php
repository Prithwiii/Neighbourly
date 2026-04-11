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

    <form method="POST" action="{{ route('messages.store', $marketplace) }}" style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px;">
        @csrf

        <div style="margin-bottom: 20px;">
            <label for="content" style="display: block; font-weight: 700; margin-bottom: 8px; color: #111827;">Message</label>
            <textarea id="content" name="content" rows="6" style="width: 100%; padding: 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 16px; color: #111827;" required>{{ old('content') }}</textarea>
            @error('content')
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
