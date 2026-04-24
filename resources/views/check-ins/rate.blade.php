@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 md:px-6 pb-10">

    <div class="rounded-3xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-2xl p-6 md:p-8">
        <div class="text-center mb-8">
            <p class="text-xs uppercase tracking-[0.2em] text-white/80">Check-in Complete</p>
            <h1 class="text-3xl md:text-4xl font-semibold text-white mt-2">Rate Your Volunteer</h1>
            <p class="text-white/90 mt-2">
                Help us improve by sharing your experience with {{ $assignment->volunteer->name }}
            </p>
        </div>

        <form method="POST" action="{{ route('check-ins.rate.store', $assignment) }}" class="space-y-6">
            @csrf

            <!-- Volunteer Info -->
            <div class="bg-white/10 rounded-xl p-4 text-center mb-6">
                <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-2xl font-semibold">
                    {{ substr($assignment->volunteer->name, 0, 1) }}
                </div>
                <h3 class="text-lg font-semibold text-white">{{ $assignment->volunteer->name }}</h3>
                <p class="text-white/80">{{ str_replace('_', ' ', ucfirst($assignment->checkInRequest->work_type)) }} - {{ $assignment->checkInRequest->title }}</p>
            </div>

            <!-- Rating Selection -->
            <div>
                <label class="block text-sm font-semibold text-white mb-4">How would you rate your experience?</label>
                <div class="flex justify-center gap-3 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" class="sr-only peer" required>
                            <span class="inline-flex items-center justify-center w-14 h-14 rounded-lg bg-white/20 border-2 border-white/30 text-2xl peer-checked:bg-emerald-600 peer-checked:border-emerald-500 peer-checked:text-white transition">
                                ★
                            </span>
                        </label>
                    @endfor
                </div>
                <p class="text-center text-xs text-white/70">1 = Poor, 5 = Excellent</p>
                @error('rating')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Review Text -->
            <div>
                <label class="block text-sm font-semibold text-white mb-2">Additional Comments (Optional)</label>
                <textarea name="review_text" rows="5"
                          placeholder="Share your experience. What went well? Any suggestions for improvement?"
                          class="w-full px-4 py-3 rounded-xl bg-white/70 border border-white/50 text-gray-800 placeholder-gray-500 focus:bg-white focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition">{{ old('review_text') }}</textarea>
                @error('review_text')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Submit -->
            <div class="flex gap-3 pt-4">
                <button type="submit"
                        class="flex-1 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition shadow-lg">
                    Submit Rating
                </button>
                <a href="{{ route('check-ins.show', $assignment->checkInRequest) }}"
                   class="flex-1 px-6 py-3 rounded-xl bg-white/20 hover:bg-white/30 border border-white/30 text-white font-semibold transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
