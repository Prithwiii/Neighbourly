@extends('layouts.app')

@section('content')

<div class="flex justify-center mt-10 px-4">

    <div class="w-full max-w-5xl p-8 rounded-3xl
                bg-white/20 backdrop-blur-xl
                border border-white/30 shadow-2xl">

        <!-- HEADER -->
        <div class="flex items-center justify-between mb-6">

            <h2 class="text-2xl font-bold text-emerald-900">
                Available Jobs
            </h2>

            <a href="{{ route('jobs.create') }}"
               class="inline-flex items-center px-4 py-2 rounded-xl
                      bg-white/30 hover:bg-white/40
                      border border-white/30 shadow
                      transition text-sm font-medium">
                + Post Job
            </a>

        </div>

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            @foreach($jobs as $job)

                <a href="{{ route('jobs.show', $job) }}"
                   class="block p-5 rounded-2xl
                          bg-white/30 hover:bg-white/40
                          border border-white/30 shadow-md
                          hover:shadow-xl transition">

                    <!-- NAME -->
                    <p class="font-semibold text-lg text-emerald-900">
                        {{ $job->name }}
                    </p>

                    <!-- LOCATION -->
                    <p class="text-sm text-gray-700 mt-1">
                        {{ $job->location }}
                    </p>

                    <!-- DATE/TIME -->
                    <p class="text-xs text-gray-600 mt-2">
                        {{ $job->job_datetime }}
                    </p>

                    <!-- STATUS -->
                    <p class="mt-3 text-xs font-semibold text-gray-800">
                        Status:
                        <span class="capitalize">
                            {{ $job->status }}
                        </span>
                    </p>

                </a>

            @endforeach

        </div>

    </div>

</div>

@endsection