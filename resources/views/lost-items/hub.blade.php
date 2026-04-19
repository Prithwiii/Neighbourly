@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center px-10">

    <div class="w-full max-w-6xl mx-auto grid md:grid-cols-2 gap-16 items-center">

        <!-- LEFT SIDE: WRITING (OUTSIDE HUB) -->
        <div class="space-y-6 animate-fadeIn">

            <h1 class="text-6xl font-bold text-white leading-tight">
                Lost & Found
            </h1>

            <p class="text-white text-lg leading-relaxed max-w-md">
                A simple way to reconnect people with their lost belongings.
                No complexity, no noise — just a direct path to help someone recover what matters.
            </p>

            <div class="space-y-2 text-l text-white">
                <p> Search items instantly</p>
                <p> Report found belongings</p>
                <p> Community-driven recovery</p>
            </div>

        </div>

        <!-- RIGHT SIDE: MINI HUB ONLY (2 OPTIONS + ANIMATION) -->
        <div class="relative">

            <!-- FLOATING ANIMATION ELEMENT -->
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-300/30 blur-3xl rounded-full animate-pulse"></div>
            <div class="absolute -bottom-10 -left-10 w-52 h-52 bg-emerald-400/20 blur-3xl rounded-full animate-pulse"></div>

            <!-- HUB CARD -->
            <div class="relative p-8 rounded-3xl
                        bg-white/30 backdrop-blur-xl
                        border border-white/40
                        shadow-2xl space-y-6">

                <!-- SMALL TITLE -->
                <h2 class="text-center text-xl font-semibold text-gray-700">
                    What would you like to do?
                </h2>

                <!-- OPTION 1 -->
                <a href="{{ route('lost-items.search') }}"
                   class="block p-6 rounded-2xl
                          bg-white/40 hover:bg-white/70
                          border border-white/40
                          shadow-md hover:shadow-xl
                          transition transform hover:scale-105">

                    <p class="text-red-600 font-semibold text-lg">
                        I have lost an item
                    </p>

                    <p class="text-gray-600 text-sm mt-1">
                        Search for something you’ve misplaced
                    </p>

                </a>

                <!-- OPTION 2 -->
                <a href="{{ route('lost-items.create') }}"
                   class="block p-6 rounded-2xl
                          bg-white/40 hover:bg-white/70
                          border border-white/40
                          shadow-md hover:shadow-xl
                          transition transform hover:scale-105">

                    <p class="text-emerald-600 font-semibold text-lg">
                        I have found an item
                    </p>

                    <p class="text-gray-600 text-sm mt-1">
                        Help return it to its owner
                    </p>

                </a>

            </div>

        </div>

    </div>
</div>

<!-- SIMPLE FADE -->
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.8s ease-out both;
}
</style>

@endsection