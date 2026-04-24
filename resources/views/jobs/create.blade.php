@extends('layouts.app')

@section('content')

<div class="flex justify-center mt-10 px-4">

    <div class="w-full max-w-lg p-8 rounded-3xl
                bg-white/20 backdrop-blur-xl
                border border-white/30 shadow-2xl">

        <h2 class="text-2xl font-bold mb-6 text-emerald-900">
            Post a Job
        </h2>

        <form method="POST" action="{{ route('jobs.store') }}" class="space-y-5">
            @csrf

            <!-- NAME -->
            <input name="name"
                   value="{{ old('name') }}"
                   placeholder="Your Name"
                   class="w-full p-3 rounded-xl bg-white/30 border border-white/30
                          focus:outline-none focus:ring-2 focus:ring-emerald-400">

            <!-- CONTACT -->
            <input name="contact"
                   value="{{ old('contact') }}"
                   placeholder="Contact Info"
                   class="w-full p-3 rounded-xl bg-white/30 border border-white/30
                          focus:outline-none focus:ring-2 focus:ring-emerald-400">

            <!-- DATE -->
            <input type="date" name="job_date"
                   value="{{ old('job_date') }}"
                   class="w-full p-3 rounded-xl bg-white/30 border border-white/30
                          focus:outline-none focus:ring-2 focus:ring-emerald-400">

            <!-- TIME -->
            <input type="time" name="job_time"
                   value="{{ old('job_time') }}"
                   class="w-full p-3 rounded-xl bg-white/30 border border-white/30
                          focus:outline-none focus:ring-2 focus:ring-emerald-400">

            <!-- LOCATION -->
            <input name="location"
                   value="{{ old('location') }}"
                   placeholder="Location"
                   class="w-full p-3 rounded-xl bg-white/30 border border-white/30
                          focus:outline-none focus:ring-2 focus:ring-emerald-400">

            <!-- DESCRIPTION -->
            <textarea name="description" rows="4"
                      placeholder="Job Description"
                      class="w-full p-3 rounded-xl bg-white/30 border border-white/30
                             focus:outline-none focus:ring-2 focus:ring-emerald-400">{{ old('description') }}</textarea>

            <!-- BUTTON -->
            <button type="submit"
                    class="w-full py-3 rounded-xl
                           bg-emerald-600 text-white font-medium
                           hover:bg-emerald-700 transition shadow-md">
                Post Job
            </button>

        </form>

    </div>
</div>

@endsection