@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 md:px-6 pb-10">

    <div class="grid md:grid-cols-3 gap-6">

        <!-- Profile Card -->
        <div class="md:col-span-1">
            <div class="rounded-2xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-lg p-6 sticky top-24">
                <div class="text-center mb-6">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-4xl font-semibold">
                        {{ substr($volunteer->name, 0, 1) }}
                    </div>
                    <h1 class="text-2xl font-semibold text-white">{{ $volunteer->name }}</h1>
                    @if($volunteer->volunteerProfile?->is_verified)
                        <p class="text-emerald-400 text-sm flex items-center justify-center gap-1 mt-2">
                            <span>✓</span> ID Verified
                        </p>
                    @endif
                </div>

                <div class="space-y-3 border-t border-white/20 pt-6">
                    <div>
                        <p class="text-xs text-white/70 uppercase">Email</p>
                        <p class="text-white font-medium break-all">{{ $volunteer->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-white/70 uppercase">Phone</p>
                        <p class="text-white font-medium">{{ $volunteer->phone_number ?? 'Not provided' }}</p>
                    </div>
                    @if($volunteer->volunteerProfile?->address)
                        <div>
                            <p class="text-xs text-white/70 uppercase">Address</p>
                            <p class="text-white font-medium">{{ $volunteer->volunteerProfile->address }}</p>
                        </div>
                    @endif
                </div>

                @if(auth()->id() === $volunteer->id)
                    <a href="{{ route('volunteers.edit-profile') }}"
                       class="block w-full mt-6 text-center px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition">
                        Edit Profile
                    </a>
                @endif
            </div>
        </div>

        <!-- Stats & Details -->
        <div class="md:col-span-2 space-y-6">

            <!-- Stats Section -->
            <div class="rounded-2xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-lg p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Volunteer Statistics</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/10 rounded-xl p-4 text-center">
                        <p class="text-3xl font-bold text-emerald-400">{{ $volunteer->volunteerProfile?->completed_check_ins ?? 0 }}</p>
                        <p class="text-white/80 text-sm">Completed Check-ins</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-4 text-center">
                        <p class="text-3xl font-bold text-emerald-400">{{ $volunteer->volunteerProfile?->average_rating ? number_format($volunteer->volunteerProfile->average_rating, 1) : 'No ratings yet' }}</p>
                        <p class="text-white/80 text-sm">Average Rating</p>
                    </div>
                </div>
            </div>

            <!-- Bio Section -->
            @if($volunteer->volunteerProfile?->bio)
                <div class="rounded-2xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-white mb-4">About</h2>
                    <p class="text-white/90 leading-relaxed">{{ $volunteer->volunteerProfile->bio }}</p>
                </div>
            @endif

            <!-- Reviews Section -->
            <div class="rounded-2xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-lg p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Reviews</h2>

                @if($reviews->isEmpty())
                    <p class="text-white/80 text-center py-6">No reviews yet</p>
                @else
                    <div class="space-y-4">
                        @foreach($reviews as $review)
                            <div class="bg-white/10 rounded-lg p-4 border border-white/20">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <p class="text-white font-medium">{{ $review->reviewer->name }}</p>
                                        <p class="text-white/70 text-xs">{{ $review->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        @for($i = 0; $i < $review->rating; $i++)
                                            <span class="text-yellow-400">★</span>
                                        @endfor
                                        @for($i = $review->rating; $i < 5; $i++)
                                            <span class="text-white/30">★</span>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->review_text)
                                    <p class="text-white/90 text-sm">{{ $review->review_text }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
