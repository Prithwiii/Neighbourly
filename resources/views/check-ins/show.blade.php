@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 md:px-6 pb-10">

    <div class="grid md:grid-cols-3 gap-6">

        <!-- Volunteer Profile Card (Left) -->
        @if($activeAssignment)
            <div class="md:col-span-1">
                <div class="rounded-2xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-lg p-6 sticky top-24">
                    <p class="text-xs uppercase tracking-[0.2em] text-white/80 mb-4">Assigned Volunteer</p>

                    <div class="text-center mb-6">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-3xl font-semibold">
                            {{ substr($activeAssignment->volunteer->name, 0, 1) }}
                        </div>
                        <h3 class="text-xl font-semibold text-white">{{ $activeAssignment->volunteer->name }}</h3>
                    </div>

                    <div class="space-y-3 mb-6 border-t border-white/20 pt-6">
                        <div>
                            <p class="text-xs text-white/70 uppercase">Phone</p>
                            <p class="text-white font-medium">{{ $activeAssignment->volunteer->phone_number ?? $checkIn->phone_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-white/70 uppercase">Email</p>
                            <p class="text-white font-medium break-all">{{ $activeAssignment->volunteer->email }}</p>
                        </div>
                        @if($activeAssignment->volunteer->volunteerProfile)
                            <div>
                                <p class="text-xs text-white/70 uppercase">Address</p>
                                <p class="text-white font-medium">{{ $activeAssignment->volunteer->volunteerProfile->address ?? 'Not specified' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-white/70 uppercase">Completed Check-ins</p>
                                <p class="text-white font-medium">{{ $activeAssignment->volunteer->volunteerProfile->completed_check_ins ?? 0 }}</p>
                            </div>
                            @if($activeAssignment->volunteer->volunteerProfile->average_rating)
                                <div>
                                    <p class="text-xs text-white/70 uppercase">Average Rating</p>
                                    <p class="text-white font-medium">{{ number_format($activeAssignment->volunteer->volunteerProfile->average_rating, 1) }}/5.0</p>
                                </div>
                            @endif
                            @if($activeAssignment->volunteer->volunteerProfile->is_verified)
                                <div class="flex items-center gap-2 text-emerald-400">
                                    <span class="text-sm">✓ ID Verified</span>
                                </div>
                            @endif
                        @endif
                    </div>

                    <a href="{{ route('volunteers.profile', $activeAssignment->volunteer) }}"
                       class="block w-full text-center px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 border border-white/30 text-white font-medium transition">
                        View Full Profile
                    </a>
                </div>
            </div>
        @else
            <div class="md:col-span-1">
                <div class="rounded-2xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-lg p-6">
                    <p class="text-center text-white/80">No volunteer assigned yet.</p>
                </div>
            </div>
        @endif

        <!-- Request Details & Messages (Right) -->
        <div class="md:col-span-2 space-y-6">

            <!-- Request Header -->
            <div class="rounded-2xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-lg p-6">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-3xl font-semibold text-white">{{ $checkIn->title }}</h1>
                        <p class="text-white/80 mt-2">by {{ $checkIn->requester->name }}</p>
                    </div>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                        {{ $checkIn->urgency_level === 'high' ? 'bg-red-600/80' : ($checkIn->urgency_level === 'medium' ? 'bg-yellow-600/80' : 'bg-blue-600/80') }} text-white">
                        {{ ucfirst($checkIn->urgency_level) }} Urgency
                    </span>
                </div>

                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-3 py-1 rounded-full text-xs bg-white/20 text-white">
                        {{ str_replace('_', ' ', ucfirst($checkIn->work_type)) }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs bg-white/20 text-white">
                        {{ $checkIn->location_name ?? 'Location not specified' }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs bg-white/20 text-white">
                        {{ ucfirst($checkIn->status) }}
                    </span>
                </div>

                <p class="text-white/90 leading-relaxed">{{ $checkIn->description }}</p>

                <div class="mt-4 pt-4 border-t border-white/20 text-sm text-white/80">
                    <p>Phone: <span class="text-white font-medium">{{ $checkIn->phone_number }}</span></p>
                    @if($checkIn->preferred_date)
                        <p>Preferred Date: <span class="text-white font-medium">{{ $checkIn->preferred_date->format('M d, Y') }}</span></p>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            @auth
                @if(!$activeAssignment && $checkIn->status === 'open')
                    <div>
                        <form method="POST" action="{{ route('check-ins.accept', $checkIn) }}" class="flex gap-3">
                            @csrf
                            <button type="submit" class="flex-1 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition shadow-lg">
                                I Can Help - Accept Request
                            </button>
                        </form>
                    </div>
                @elseif($activeAssignment && auth()->id() === $checkIn->requester->id && $activeAssignment->status === 'pending')
                    <div class="rounded-2xl bg-yellow-600/20 border border-yellow-600/50 shadow-lg p-6">
                        <p class="text-yellow-300 mb-4">{{ $activeAssignment->volunteer->name }} has accepted your request. Please confirm them as your volunteer.</p>
                        <div class="flex gap-3">
                            <form method="POST" action="{{ route('check-ins.confirm', $activeAssignment) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition shadow-lg">
                                Confirm Volunteer
                            </button>
                        </form>
                        <form method="POST" action="{{ route('check-ins.cancel', $activeAssignment) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full px-6 py-3 rounded-xl bg-red-600/20 hover:bg-red-600/30 border border-red-600/50 text-red-300 font-semibold transition">
                                    Decline
                                </button>
                        </form>
                    </div>
                    </div>
                @elseif($activeAssignment && $activeAssignment->status === 'confirmed')
                    <div class="rounded-2xl bg-green-600/20 border border-green-600/50 shadow-lg p-6">
                        <p class="text-green-300 mb-4">Check-in in progress with {{ $activeAssignment->volunteer->name }}</p>
                        @if(auth()->id() === $activeAssignment->volunteer->id)
                            <form method="POST" action="{{ route('check-ins.complete', $activeAssignment) }}">
                                @csrf
                                <button type="submit" class="w-full px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition shadow-lg">
                                    Mark as Complete
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            @endauth

            <!-- Messages Section -->
            @if($activeAssignment)
                <div class="rounded-2xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-white mb-4">Messages</h2>

                    <div class="bg-white/10 rounded-xl p-4 mb-4 max-h-64 overflow-y-auto space-y-3">
                        @if($checkIn->messages->isEmpty())
                            <p class="text-white/70 text-center text-sm">No messages yet</p>
                        @else
                            @foreach($checkIn->messages->sortBy('created_at') as $message)
                                <div class="flex {{ auth()->id() === $message->sender_id ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-xs px-4 py-2 rounded-lg {{ auth()->id() === $message->sender_id ? 'bg-emerald-600/80' : 'bg-white/20' }}">
                                        <p class="text-xs text-white/80 mb-1">{{ $message->sender->name }}</p>
                                        <p class="text-white">{{ $message->message }}</p>
                                        <p class="text-xs text-white/60 mt-1">{{ $message->created_at->format('M d, g:i A') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    @auth
                        <form method="POST" action="{{ route('check-ins.messages.store', $checkIn) }}" class="flex gap-2">
                            @csrf
                            <input type="text" name="message" placeholder="Send a message..."
                                   class="flex-1 px-4 py-2 rounded-lg bg-white/70 border border-white/50 text-gray-800 placeholder-gray-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-400 transition"
                                   required>
                            <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition">
                                Send
                            </button>
                        </form>
                    @endauth
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
