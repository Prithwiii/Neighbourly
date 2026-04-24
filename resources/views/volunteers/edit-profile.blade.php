@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 md:px-6 pb-10">

    <div class="rounded-3xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-2xl p-6 md:p-8">
        <div class="mb-8">
            <p class="text-xs uppercase tracking-[0.2em] text-white/80">Account</p>
            <h1 class="text-3xl md:text-4xl font-semibold text-white mt-2">Edit Volunteer Profile</h1>
            <p class="text-white/90 mt-2">
                Keep your profile updated so others know about your experience and availability.
            </p>
        </div>

        <form method="POST" action="{{ route('volunteers.update-profile') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-semibold text-white mb-2">Bio (Optional)</label>
                <textarea name="bio" rows="4"
                          placeholder="Tell others about your experience and what kinds of help you enjoy providing..."
                          class="w-full px-4 py-3 rounded-xl bg-white/70 border border-white/50 text-gray-800 placeholder-gray-500 focus:bg-white focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition">{{ old('bio', auth()->user()->volunteerProfile?->bio) }}</textarea>
                @error('bio')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-2">Address</label>
                <input type="text" name="address" value="{{ old('address', auth()->user()->volunteerProfile?->address) }}"
                       placeholder="Your address"
                       class="w-full px-4 py-3 rounded-xl bg-white/70 border border-white/50 text-gray-800 placeholder-gray-500 focus:bg-white focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition">
                @error('address')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-2">Verified ID (Optional)</label>
                <p class="text-white/80 text-sm mb-3">
                    Upload a copy of your ID to verify your identity and build trust with those needing help.
                </p>
                <input type="file" name="verified_id" accept="image/*,.pdf"
                       class="w-full px-4 py-3 rounded-xl bg-white/70 border border-white/50 text-gray-800 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-400 transition">
                @error('verified_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                @if(auth()->user()->volunteerProfile?->verified_id_path)
                    <p class="text-emerald-400 text-xs mt-2">✓ ID already verified</p>
                @endif
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit"
                        class="flex-1 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition shadow-lg">
                    Save Changes
                </button>
                <a href="{{ route('volunteers.profile', auth()->user()) }}"
                   class="flex-1 px-6 py-3 rounded-xl bg-white/20 hover:bg-white/30 border border-white/30 text-white font-semibold transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
