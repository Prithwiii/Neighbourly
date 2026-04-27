@extends('layouts.app')

@section('content')

<div class="flex justify-center mt-10 px-4">

    <div class="w-full max-w-5xl p-8 rounded-3xl
                bg-white/20 backdrop-blur-xl
                border border-white/30 shadow-2xl">

        <!-- HEADER -->
        <div class="flex items-center justify-between mb-6">

            <h2 class="text-2xl font-bold text-emerald-900">
                Available Workers
            </h2>

            <a href="{{ route('enlistings.create') }}"
               class="inline-flex items-center px-4 py-2 rounded-xl
                      bg-white/30 hover:bg-white/40
                      border border-white/30 shadow
                      transition text-sm font-medium">
                + Enlist Yourself
            </a>

        </div>

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            @foreach($enlistings as $e)

                <a href="{{ route('enlistings.show', $e) }}"
                   class="block p-5 rounded-2xl
                          bg-white/30 hover:bg-white/40
                          border border-white/30 shadow-md
                          hover:shadow-xl transition">

                    <p class="font-semibold text-lg text-emerald-900">
                        {{ $e->name }}
                    </p>

                    <p class="text-sm text-gray-700 mt-1">
                        {{ $e->preferred_job }}
                    </p>

                    <p class="text-xs text-gray-600 mt-3 leading-relaxed">
                        {{ $e->availability }}
                    </p>

                </a>

            @endforeach

        </div>

    </div>

</div>

@endsection