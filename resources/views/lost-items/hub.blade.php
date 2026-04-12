@extends('layouts.app')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center relative">

    <!-- BACKGROUND FEEL (inherits from layout) -->

    <!-- GLASS HUB CARD -->
    <div class="w-[650px] p-10 rounded-3xl
                bg-white/25 backdrop-blur-xl
                border border-white/30
                shadow-2xl text-center">

        <h1 class="text-4xl font-bold text-emerald-700 mb-10">
            Lost & Found 
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- LOST ITEM -->
            <a href="{{ route('lost-items.search') }}"
               class="p-8 rounded-2xl
                      bg-white/30 hover:bg-white/40
                      backdrop-blur-md
                      border border-white/30
                      shadow-md hover:shadow-xl
                      transition transform hover:scale-105">

                <h2 class="text-xl font-semibold text-red-600">
                    I have lost an item!
                </h2>

                <p class="text-gray-600 mt-2">
                    Search for your missing item
                </p>
            </a>

            <!-- FOUND ITEM -->
            <a href="{{ route('lost-items.create') }}"
               class="p-8 rounded-2xl
                      bg-white/30 hover:bg-white/40
                      backdrop-blur-md
                      border border-white/30
                      shadow-md hover:shadow-xl
                      transition transform hover:scale-105">

                <h2 class="text-xl font-semibold text-emerald-600">
                    I have found an item!
                </h2>

                <p class="text-gray-600 mt-2">
                    Report a found item
                </p>
            </a>

        </div>

    </div>

</div>

@endsection