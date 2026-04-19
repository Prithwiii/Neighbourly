@extends('layouts.app')

@section('content')

<div class="flex justify-center mt-10">
    <div class="w-[600px] p-8 rounded-3xl bg-white/20 backdrop-blur-xl border border-white/30 shadow-2xl">

        <h2 class="text-2xl font-bold mb-6 text-emerald-900">Post a Job</h2>

        <form method="POST" action="{{ route('jobs.store') }}" class="space-y-4">
            @csrf

            <input name="name" placeholder="Your Name"
                    class="w-full p-3 rounded-xl bg-white/30 border border-white/30">

            <input name="contact" placeholder="Contact Info"
                    class="w-full p-3 rounded-xl bg-white/30 border border-white/30">

            {{-- <input type="datetime-local" name="job_datetime"
                   class="w-full p-3 rounded-xl bg-white/30 border border-white/30"> --}}
            <input type="date" name="job_date"
                class="w-full p-3 rounded-xl bg-white/30 border border-white/30">

            <input type="time" name="job_time"
                class="w-full p-3 rounded-xl bg-white/30 border border-white/30">
                
            <input name="location" placeholder="Location"
                    class="w-full p-3 rounded-xl bg-white/30 border border-white/30">

            <textarea name="description" placeholder="Job Description"
                    class="w-full p-3 rounded-xl bg-white/30 border border-white/30"></textarea>

            <button class="w-full py-3 rounded-xl bg-white/30 hover:bg-white/40 border border-white/30 transition">
                Post Job
            </button>
        </form>

    </div>
</div>

@endsection