@extends('layouts.app')

@section('content')

<div class="flex justify-center mt-10">
    <div class="w-[900px] p-8 rounded-3xl bg-white/20 backdrop-blur-xl border border-white/30 shadow-2xl">

        <div class="flex justify-between mb-6">
            <h2 class="text-2xl font-bold text-emerald-900">Available Workers</h2>
            <a href="{{ route('enlistings.create') }}"
               class="px-4 py-2 rounded-xl bg-white/30 hover:bg-white/40 border border-white/30 shadow transition">
                + Enlist Yourself
            </a>
        </div>

        <div class="grid grid-cols-2 gap-5">

            @foreach($enlistings as $e)
                <a href="{{ route('enlistings.show', $e) }}"
                   class="p-5 rounded-2xl bg-white/30 hover:bg-white/40 border border-white/30 shadow-md hover:shadow-xl transition">

                    <p class="font-semibold text-lg text-emerald-900">{{ $e->name }}</p>
                    <p class="text-sm text-gray-700">{{ $e->preferred_job }}</p>
                    <p class="text-xs mt-2 text-gray-600">{{ $e->availability }}</p>

                </a>
            @endforeach

        </div>

    </div>
</div>

@endsection