@extends('layouts.app')

@section('content')

<div class="flex justify-center mt-10 px-4">

    <div class="w-full max-w-lg p-8 rounded-3xl
                bg-white/20 backdrop-blur-xl
                border border-white/30 shadow-2xl">

        <h2 class="text-2xl font-bold mb-6 text-emerald-900">
            Enlist Yourself
        </h2>

        <form method="POST" action="{{ route('enlistings.store') }}" class="space-y-5">
            @csrf

            <!-- NAME -->
            <div>
                <label class="text-sm text-gray-700 mb-1 block">Name</label>
                <input name="name" value="{{ old('name') }}"
                       placeholder="Your Name"
                       class="w-full p-3 rounded-xl
                              bg-white/30 border border-white/30
                              focus:outline-none focus:ring-2 focus:ring-emerald-400">

                @error('name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- CONTACT -->
            <div>
                <label class="text-sm text-gray-700 mb-1 block">Contact</label>
                <input name="contact" value="{{ old('contact') }}"
                       placeholder="Contact Info"
                       class="w-full p-3 rounded-xl
                              bg-white/30 border border-white/30
                              focus:outline-none focus:ring-2 focus:ring-emerald-400">

                @error('contact')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- PREFERRED JOB -->
            <div>
                <label class="text-sm text-gray-700 mb-1 block">Preferred Job</label>
                <input name="preferred_job" value="{{ old('preferred_job') }}"
                       placeholder="Preferred Job"
                       class="w-full p-3 rounded-xl
                              bg-white/30 border border-white/30
                              focus:outline-none focus:ring-2 focus:ring-emerald-400">

                @error('preferred_job')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- AVAILABILITY -->
            <div>
                <label class="text-sm text-gray-700 mb-1 block">Availability</label>
                <textarea name="availability" rows="4"
                          placeholder="Days & hours you're available"
                          class="w-full p-3 rounded-xl
                                 bg-white/30 border border-white/30
                                 focus:outline-none focus:ring-2 focus:ring-emerald-400">{{ old('availability') }}</textarea>

                @error('availability')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- SUBMIT -->
            <button type="submit"
                    class="w-full py-3 rounded-xl
                           bg-emerald-600 text-white font-medium
                           hover:bg-emerald-700 transition shadow-md">
                Submit Enlistment
            </button>

        </form>

    </div>
</div>

@endsection