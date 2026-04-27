@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <h1>{{ $serviceProvider->full_name }} <span style="color:green;">✅ Verified</span></h1>
    <p><strong>Category:</strong> {{ $serviceProvider->service_category }}</p>
    <p><strong>Location:</strong> {{ $serviceProvider->location }}</p>
    <p><strong>Phone:</strong> {{ $serviceProvider->phone }}</p>
    <p><strong>Experience:</strong> {{ $serviceProvider->experience_years }} years</p>
    <p><strong>Availability:</strong> {{ ucfirst($serviceProvider->availability_status) }} {{ $serviceProvider->availability_details ? '- '.$serviceProvider->availability_details : '' }}</p>
    <p><strong>Description:</strong> {{ $serviceProvider->description }}</p>
    <p><strong>Average Rating:</strong> {{ $serviceProvider->averageRating() }} / 5</p>
    <p><strong>Total Reviews:</strong> {{ $serviceProvider->reviews_count }}</p>

    @auth
        @if(auth()->id() !== $serviceProvider->user_id)
            <div style="border:1px solid #ddd; border-radius:8px; padding:14px; margin:16px 0;">
                <h3>Rate & Review</h3>
                <form method="POST" action="{{ route('services.reviews.store', $serviceProvider) }}" style="display:grid; gap:10px;">
                    @csrf
                    <div>
                        <label>Rating</label><br>
                        <select name="rating" required style="padding:8px; width:120px;">
                            <option value="">Select</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}">{{ $i }} Star</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label>Review</label><br>
                        <textarea name="review" rows="3" placeholder="Share your experience..." style="width:100%; padding:8px;"></textarea>
                    </div>
                    <button type="submit" style="padding:8px 12px; width:160px;">Submit Review</button>
                </form>
            </div>
        @endif
    @endauth

    <h3>Recent Reviews</h3>
    @forelse($serviceProvider->reviews->sortByDesc('created_at') as $review)
        <div style="border:1px solid #eee; border-radius:8px; padding:12px; margin-bottom:10px;">
            <p style="margin:0;"><strong>{{ $review->user->name }}</strong> - {{ $review->rating }}/5</p>
            <p style="margin:6px 0;">{{ $review->review ?: 'No written comment.' }}</p>
            <small>{{ $review->created_at->diffForHumans() }}</small>
        </div>
    @empty
        <p>No reviews yet.</p>
    @endforelse
</div>
@endsection
