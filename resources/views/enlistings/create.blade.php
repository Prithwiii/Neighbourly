@extends('layouts.app')

@section('content')

<div class="flex justify-center mt-10">
    <div class="w-[600px] p-8 rounded-3xl bg-white/20 backdrop-blur-xl border border-white/30 shadow-2xl">

        <h2 class="text-2xl font-bold mb-6 text-emerald-900">Enlist Yourself</h2>

        <form method="POST" action="{{ route('enlistings.store') }}" class="space-y-4">
            @csrf

            <input name="name" placeholder="Your Name"
                   class="w-full p-3 rounded-xl bg-white/30 border border-white/30">

            <input name="contact" placeholder="Contact Info"
                   class="w-full p-3 rounded-xl bg-white/30 border border-white/30">

            <input name="preferred_job" placeholder="Preferred Job"
                   class="w-full p-3 rounded-xl bg-white/30 border border-white/30">

            <textarea name="availability" placeholder="Availability (days & hours)"
                      class="w-full p-3 rounded-xl bg-white/30 border border-white/30"></textarea>

            <button class="w-full py-3 rounded-xl bg-white/30 hover:bg-white/40 border border-white/30 transition">
                Submit
            </button>
        </form>

    </div>
</div>

@endsection