@extends('layouts.app')

@section('content')

<div class="flex justify-center mt-10">
    <div class="w-[900px] p-8 rounded-3xl bg-white/20 backdrop-blur-xl border border-white/30 shadow-2xl">

        <div class="flex justify-between mb-6">
            <h2 class="text-2xl font-bold text-emerald-900">Available Jobs</h2>
            <a href="{{ route('jobs.create') }}"
               class="px-4 py-2 rounded-xl bg-white/30 hover:bg-white/40 border border-white/30 shadow transition">
                + Post Job
            </a>
        </div>

        <div class="grid grid-cols-2 gap-5">

            @foreach($jobs as $job)
                <a href="{{ route('jobs.show', $job) }}"
                   class="p-5 rounded-2xl bg-white/30 hover:bg-white/40 border border-white/30 shadow-md hover:shadow-xl transition">

                    <p class="font-semibold text-lg text-emerald-900">{{ $job->name }}</p>
                    <p class="text-sm text-gray-700">{{ $job->location }}</p>
                    <p class="text-xs text-gray-600 mt-1">{{ $job->job_datetime }}</p>

                    <p class="mt-2 text-xs font-semibold">
                        Status: {{ ucfirst($job->status) }}
                    </p>

                </a>
            @endforeach

        </div>

    </div>
</div>

@endsection