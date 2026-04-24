@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 md:px-6 pb-10">

    <section class="rounded-3xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-2xl p-6 md:p-8">
        <div class="mb-8">
            <p class="text-xs uppercase tracking-[0.2em] text-white/80">Community Help</p>
            <h1 class="text-3xl md:text-4xl font-semibold text-white mt-2">Post a Check-in Request</h1>
            <p class="text-white/90 mt-2">
                Tell your neighbours what kind of help you need. Include details so volunteers can assist you better.
            </p>
        </div>

        <form method="POST" action="{{ route('check-ins.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-white mb-2">What do you need help with?</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       placeholder="e.g., Elderly care check-in, Childcare assistance"
                       class="w-full px-4 py-3 rounded-xl bg-white/70 border border-white/50 text-gray-800 placeholder-gray-500 focus:bg-white focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition">
                @error('title')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-2">Description</label>
                <textarea name="description" rows="5" required
                          placeholder="Provide details about what help you need..."
                          class="w-full px-4 py-3 rounded-xl bg-white/70 border border-white/50 text-gray-800 placeholder-gray-500 focus:bg-white focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">Type of Work</label>
                    <select name="work_type" required
                            class="w-full px-4 py-3 rounded-xl bg-white/70 border border-white/50 text-gray-800 focus:bg-white focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition">
                        <option value="">Select work type...</option>
                        <option value="elderly_care" {{ old('work_type') === 'elderly_care' ? 'selected' : '' }}>Elderly Care</option>
                        <option value="childcare" {{ old('work_type') === 'childcare' ? 'selected' : '' }}>Childcare</option>
                        <option value="home_help" {{ old('work_type') === 'home_help' ? 'selected' : '' }}>Home Help</option>
                        <option value="companionship" {{ old('work_type') === 'companionship' ? 'selected' : '' }}>Companionship</option>
                        <option value="medical_support" {{ old('work_type') === 'medical_support' ? 'selected' : '' }}>Medical Support</option>
                        <option value="other" {{ old('work_type') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('work_type')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white mb-2">Urgency Level</label>
                    <select name="urgency_level" required
                            class="w-full px-4 py-3 rounded-xl bg-white/70 border border-white/50 text-gray-800 focus:bg-white focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition">
                        <option value="">Select urgency...</option>
                        <option value="low" {{ old('urgency_level') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('urgency_level') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('urgency_level') === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('urgency_level')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-2">Phone Number</label>
                <input type="tel" name="phone_number" value="{{ old('phone_number') }}" required
                       placeholder="Your contact number"
                       class="w-full px-4 py-3 rounded-xl bg-white/70 border border-white/50 text-gray-800 placeholder-gray-500 focus:bg-white focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition">
                @error('phone_number')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-2">Preferred Date (Optional)</label>
                <input type="date" name="preferred_date" value="{{ old('preferred_date') }}"
                       class="w-full px-4 py-3 rounded-xl bg-white/70 border border-white/50 text-gray-800 focus:bg-white focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition">
                @error('preferred_date')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit"
                        class="flex-1 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition shadow-lg">
                    Post Request
                </button>
                <a href="{{ route('check-ins.hub') }}"
                   class="flex-1 px-6 py-3 rounded-xl bg-white/20 hover:bg-white/30 border border-white/30 text-white font-semibold transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </section>

</div>
@endsection
