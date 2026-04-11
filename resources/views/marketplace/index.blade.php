@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div style="max-width: 900px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="margin: 0;">Marketplace</h1>
        <a href="{{ route('marketplace.create') }}" style="background-color: #007bff; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            ➕ Create Listing
        </a>
    </div>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 12px 20px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search and Filter Form -->
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #dee2e6;">
        <form method="GET" action="{{ route('marketplace.index') }}" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: end;">
            <div style="flex: 1; min-width: 200px;">
                <label for="search" style="display: block; margin-bottom: 5px; font-weight: bold;">Search by Title:</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Enter title..." style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="min-width: 120px;">
                <label for="min_price" style="display: block; margin-bottom: 5px; font-weight: bold;">Min Price:</label>
                <input type="number" id="min_price" name="min_price" value="{{ request('min_price') }}" step="0.01" min="0" placeholder="0.00" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="min-width: 120px;">
                <label for="max_price" style="display: block; margin-bottom: 5px; font-weight: bold;">Max Price:</label>
                <input type="number" id="max_price" name="max_price" value="{{ request('max_price') }}" step="0.01" min="0" placeholder="0.00" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background-color: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">🔍 Search</button>
                <a href="{{ route('marketplace.index') }}" style="background-color: #6c757d; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none;">Clear</a>
            </div>
        </form>
    </div>

    @forelse($items as $item)
        <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; gap: 20px;">
                <!-- Image -->
                <div style="flex-shrink: 0;">
                    @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" alt="Item photo" style="width: 150px; height: 150px; object-fit: cover; border-radius: 5px;">
                    @else
                        <div style="width: 150px; height: 150px; background-color: #f0f0f0; border-radius: 5px; display: flex; align-items: center; justify-content: center; color: #666;">
                            📷 No Image
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                        <h2 style="margin: 0; color: #333;">
                            <a href="{{ route('marketplace.show', $item) }}" style="text-decoration: none; color: inherit;">{{ $item->title }}</a>
                        </h2>
                        <div style="font-size: 18px; font-weight: bold; color: #28a745;">
                            {{ number_format($item->price, 2) }} tk
                        </div>
                    </div>

                    <p style="color: #555; margin: 10px 0; line-height: 1.6;">
                        {{ \Illuminate\Support\Str::limit($item->description, 200) }}
                    </p>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; padding-top: 10px; border-top: 1px solid #eee; font-size: 14px; color: #666;">
                        <div>
                            <strong>👤 {{ $item->user->name }}</strong> | 
                            <span>🕒 {{ $item->created_at->diffForHumans() }}</span>
                        </div>
                        @if($item->user_id === auth()->id())
                            <div style="display: flex; gap: 10px;">
                                <a href="{{ route('marketplace.edit', $item) }}" style="color: #007bff; text-decoration: none;">✏️ Edit</a>
                                <form method="POST" action="{{ route('marketplace.destroy', $item) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this listing?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; text-decoration: underline;">🗑️ Delete</button>
                                </form>
                            </div>
                        @else
                            <button type="button" style="background-color: #28a745; color: white; border: none; padding: 10px 18px; border-radius: 5px; cursor: pointer; font-weight: bold;">💬 Send Message</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 50px; background-color: #f8f9fa; border-radius: 8px;">
            <h3>No listings found</h3>
            <p>Be the first to create a listing in the marketplace!</p>
            <a href="{{ route('marketplace.create') }}" style="background-color: #007bff; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: 10px;">Create Listing</a>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($items->hasPages())
        <div style="text-align: center; margin-top: 30px;">
            {{ $items->links() }}
        </div>
    @endif
</div>
@endsection