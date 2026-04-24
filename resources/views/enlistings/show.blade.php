@extends('layouts.app')

@section('content')

<div class="flex justify-center mt-10 px-4">

    <div class="w-full max-w-lg p-8 rounded-3xl
                bg-white/20 backdrop-blur-xl
                border border-white/30 shadow-2xl">

        <!-- TITLE -->
        <h2 class="text-2xl font-bold text-emerald-900 mb-6">
            {{ $enlisting->name }}
        </h2>

        <!-- DETAILS -->
        <div class="space-y-4 text-gray-800">

            <p>
                <span class="font-semibold">Contact:</span>
                <span class="ml-1">{{ $enlisting->contact }}</span>
            </p>

            <p>
                <span class="font-semibold">Preferred Job:</span>
                <span class="ml-1">{{ $enlisting->preferred_job }}</span>
            </p>

            <p>
                <span class="font-semibold">Availability:</span>
                <span class="ml-1">{{ $enlisting->availability }}</span>
            </p>

        </div>

        <!-- ACTIONS -->
        <div class="mt-8 flex items-center justify-between">

            <!-- BACK -->
            <a href="{{ route('enlistings.index') }}"
               class="px-4 py-2 rounded-xl
                      bg-white/30 hover:bg-white/40
                      border border-white/30 shadow
                      transition text-sm">
                Back
            </a>

            <!-- DELETE (same logic preserved) -->
            @if(auth()->id() === $enlisting->user_id || auth()->user()->is_admin)

                <form method="POST" action="{{ route('enlistings.destroy', $enlisting) }}">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="px-4 py-2 rounded-xl
                                   bg-red-500/70 hover:bg-red-600/80
                                   text-white shadow transition text-sm">
                        Delete
                    </button>
                </form>

            @endif

        </div>

    </div>

</div>

@endsection