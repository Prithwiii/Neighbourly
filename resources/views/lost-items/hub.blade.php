@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center px-4 ">

    <!-- MAIN HUB CARD -->
    <div class="w-full max-w-2xl p-10 rounded-3xl
                bg-white/30 backdrop-blur-xl
                border border-white/40
                shadow-2xl text-center">

        <!-- TITLE -->
        <h1 class="text-4xl font-bold text-emerald-700">
            Lost & Found
        </h1>

        <!-- DESCRIPTION -->
        <p class="text-gray-600 mt-4 leading-relaxed text-lg">
            A simple way to reconnect people with their lost belongings.
            Report what you’ve lost or share items you’ve found to help someone in your community.
        </p>

        <!-- SMALL INFO TEXT -->
        <div class="mt-6 text-sm text-gray-500 space-y-1">
            <p>🔍 Search lost items instantly</p>
            <p>📦 Report found belongings</p>
            <p>🤝 Help your community reconnect</p>
        </div>

        <!-- ACTION BOXES -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10">

            <!-- LOST ITEM -->
            <a href="{{ route('lost-items.search') }}"
               class="p-8 rounded-2xl
                      bg-white/40 hover:bg-white/60
                      backdrop-blur-md
                      border border-white/40
                      shadow-md hover:shadow-xl
                      transition transform hover:scale-105">

                <h2 class="text-xl font-semibold text-red-600">
                    I have lost an item
                </h2>

                <p class="text-gray-600 mt-2">
                    Search through reported items
                </p>

            </a>

            <!-- FOUND ITEM -->
            <a href="{{ route('lost-items.create') }}"
               class="p-8 rounded-2xl
                      bg-white/40 hover:bg-white/60
                      backdrop-blur-md
                      border border-white/40
                      shadow-md hover:shadow-xl
                      transition transform hover:scale-105">

                <h2 class="text-xl font-semibold text-emerald-600">
                    I have found an item
                </h2>

                <p class="text-gray-600 mt-2">
                    Help return it to its owner
                </p>

            </a>

        </div>

    </div>

</div>

@endsection