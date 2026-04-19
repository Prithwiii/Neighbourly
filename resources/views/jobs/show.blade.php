@extends('layouts.app')

@section('content')

<div class="flex justify-center mt-10">
    <div class="w-[700px] p-8 rounded-3xl bg-white/20 backdrop-blur-xl border border-white/30 shadow-2xl">

        <h2 class="text-2xl font-bold text-emerald-900 mb-4">Job Details</h2>

        <div class="space-y-2 text-gray-800">
            <p><strong>Name:</strong> {{ $job->name }}</p>
            <p><strong>Contact:</strong> {{ $job->contact }}</p>
            <p><strong>Date:</strong> {{ $job->job_datetime }}</p>
            <p><strong>Location:</strong> {{ $job->location }}</p>
            <p><strong>Description:</strong> {{ $job->description }}</p>
            <p><strong>Status:</strong> {{ ucfirst($job->status) }}</p>
        </div>

        <hr class="my-5">

        {{-- Selected Worker --}}
        @if($job->selectedEnlisting)
            <p class="mb-3 text-emerald-900 font-semibold">
                Selected Worker: {{ $job->selectedEnlisting->name }}
            </p>
        @endif

        {{-- Select Worker (Employer only) --}}
        @if(auth()->id() === $job->user_id && !$job->selected_enlisting_id)

            <div class="space-y-2">
                @foreach(\App\Models\Enlisting::all() as $e)
                    <form method="POST" action="{{ route('jobs.selectWorker', $job) }}">
                        @csrf
                        <input type="hidden" name="enlisting_id" value="{{ $e->id }}">
                        <button class="w-full p-2 rounded-xl bg-white/30 hover:bg-white/40 border border-white/30">
                            Select {{ $e->name }} ({{ $e->preferred_job }})
                        </button>
                    </form>
                @endforeach
            </div>

        @endif

        <hr class="my-5">

        {{-- Confirm Buttons --}}
        @php $confirmation = $job->confirmation; @endphp

        @if(auth()->id() === $job->user_id && !$confirmation?->employer_confirmed)
            <form method="POST" action="{{ route('jobs.confirm', $job) }}">
                @csrf
                <button class="w-full py-2 rounded-xl bg-blue-400/60 hover:bg-blue-500/70 text-white">
                    Confirm as Employer
                </button>
            </form>
        @endif

        @if($job->selectedEnlisting && auth()->id() === $job->selectedEnlisting->user_id && !$confirmation?->worker_confirmed)
            <form method="POST" action="{{ route('jobs.confirm', $job) }}">
                @csrf
                <button class="w-full py-2 mt-2 rounded-xl bg-green-400/60 hover:bg-green-500/70 text-white">
                    Confirm as Worker
                </button>
            </form>
        @endif

    </div>
</div>

@endsection