@extends('layouts.app')

@section('content')

<div class="flex justify-center mt-10">
    <div class="w-[600px] p-8 rounded-3xl bg-white/20 backdrop-blur-xl border border-white/30 shadow-2xl">

        <h2 class="text-2xl font-bold text-emerald-900 mb-4">{{ $enlisting->name }}</h2>

        <div class="space-y-3 text-gray-800">
            <p><strong>Contact:</strong> {{ $enlisting->contact }}</p>
            <p><strong>Preferred Job:</strong> {{ $enlisting->preferred_job }}</p>
            <p><strong>Availability:</strong> {{ $enlisting->availability }}</p>
        </div>

        <div class="mt-6 flex justify-between">

            <a href="{{ route('enlistings.index') }}"
               class="px-4 py-2 rounded-xl bg-white/30 hover:bg-white/40 border border-white/30">
                Back
            </a>

            @if(auth()->id() === $enlisting->user_id || auth()->user()->is_admin)
                <form method="POST" action="{{ route('enlistings.destroy', $enlisting) }}">
                    @csrf
                    @method('DELETE')
                    <button class="px-4 py-2 rounded-xl bg-red-400/60 hover:bg-red-500/70 text-white">
                        Delete
                    </button>
                </form>
            @endif

        </div>

    </div>
</div>

@endsection