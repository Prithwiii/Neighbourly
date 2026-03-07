@extends('layouts.app')

@section('content')

<h2>Search Items</h2>

<form method="GET" action="{{ route('lost-items.search') }}">
    <input type="text" name="query" value="{{ request('query') }}" placeholder="Search by item name" required>
    <button type="submit">Search</button>
</form>

<hr>

<h1>All Lost Items</h1>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

@if($items->isEmpty())
    <p>No items found matching your search.</p>
@endif

@foreach($items as $item)
    <div class="lost-item">
        <div class="lost-item-info">
            <p><strong>Name:</strong> {{ $item->username }}</p>
            <p><strong>Phone:</strong> {{ $item->phone }}</p>
            <p><strong>Description:</strong> {{ $item->description }}</p>
            <p><strong>Location:</strong> {{ $item->location }}</p>
            <p><strong>Date:</strong> {{ $item->date_lost }}</p>
        </div>

        @if($item->image)
            <div class="lost-item-image">
                <img src="{{ asset('storage/lost_items/' . $item->image) }}" alt="Lost Item Image">
            </div>
        @endif
    </div>
@endforeach
@endsection
