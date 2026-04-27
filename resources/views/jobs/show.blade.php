@extends('layouts.app')

@section('content')

<div class="flex justify-center mt-10 px-4">

    <div class="w-full max-w-2xl p-8 rounded-3xl
                bg-white/20 backdrop-blur-xl
                border border-white/30 shadow-2xl">

        <!-- TITLE -->
        <h2 class="text-2xl font-bold text-emerald-900 mb-6">
            Job Details
        </h2>

        <!-- BASIC INFO -->
        <div class="space-y-3 text-gray-800">

            <p><span class="font-semibold">Name:</span> {{ $job->name }}</p>
            <p><span class="font-semibold">Contact:</span> {{ $job->contact }}</p>
            <p><span class="font-semibold">Date:</span> {{ $job->job_datetime }}</p>
            <p><span class="font-semibold">Location:</span> {{ $job->location }}</p>
            <p><span class="font-semibold">Description:</span> {{ $job->description }}</p>
            <p><span class="font-semibold">Status:</span> {{ ucfirst($job->status) }}</p>

        </div>

        <hr class="my-6 border-white/30">

        <!-- SELECTED WORKER -->
        @if($job->selectedEnlisting)
            <div class="mb-4 p-3 rounded-xl bg-emerald-100/40 border border-emerald-200">
                <p class="text-emerald-900 font-semibold">
                    Selected Worker: {{ $job->selectedEnlisting->name }}
                </p>
            </div>
        @endif

        <!-- SELECT WORKER (EMPLOYER ONLY) -->
        @if(auth()->id() === $job->user_id && !$job->selected_enlisting_id)

            <div class="space-y-2 mb-6">

                @foreach(\App\Models\Enlisting::all() as $e)

                    <form method="POST" action="{{ route('jobs.selectWorker', $job) }}">
                        @csrf
                        <input type="hidden" name="enlisting_id" value="{{ $e->id }}">

                        <button type="submit"
                                class="w-full p-3 rounded-xl
                                       bg-white/30 hover:bg-white/40
                                       border border-white/30 shadow
                                       transition text-left">

                            <span class="font-medium text-gray-800">
                                Select {{ $e->name }}
                            </span>

                            <span class="text-xs text-gray-600 block">
                                {{ $e->preferred_job }}
                            </span>

                        </button>
                    </form>

                @endforeach

            </div>

        @endif

        <hr class="my-6 border-white/30">

        <!-- CONFIRMATIONS -->
        @php $confirmation = $job->confirmation; @endphp

        <div class="space-y-3">

            @if(auth()->id() === $job->user_id && !$confirmation?->employer_confirmed)

                <form method="POST" action="{{ route('jobs.confirm', $job) }}">
                    @csrf

                    <button type="submit"
                            class="w-full py-3 rounded-xl
                                   bg-blue-500/70 hover:bg-blue-600/80
                                   text-white shadow transition">
                        Confirm as Employer
                    </button>
                </form>

            @endif

            @if($job->selectedEnlisting && auth()->id() === $job->selectedEnlisting->user_id && !$confirmation?->worker_confirmed)

                <form method="POST" action="{{ route('jobs.confirm', $job) }}">
                    @csrf

                    <button type="submit"
                            class="w-full py-3 rounded-xl
                                   bg-emerald-500/70 hover:bg-emerald-600/80
                                   text-white shadow transition">
                        Confirm as Worker
                    </button>
                </form>

            @endif

        </div>

    </div>

</div>

@endsection