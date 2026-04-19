@extends('layouts.app')

@section('content')

<div class="min-h-screen py-10">

    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-10">

        <!-- LEFT: LIST SECTION -->
        <div class="md:col-span-2 space-y-6">

            

            <!-- SEARCH -->
            <form method="GET" action="{{ route('lost-items.search') }}"
                  class="flex gap-3">

                <input type="text" name="query"
                    value="{{ request('query') }}"
                    placeholder="Search lost items..."
                    class="flex-1 p-3 rounded-xl bg-white border border-gray-200
                           focus:outline-none focus:ring-2 focus:ring-emerald-400">

                <button class="px-5 py-3 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 transition">
                    Search
                </button>

            </form>

            <!-- SUCCESS -->
            @if(session('success'))
                <div class="p-3 rounded-xl bg-green-100 text-green-700 text-center">
                    {{ session('success') }}
                </div>
            @endif

            <!-- EMPTY -->
            @if($items->isEmpty())
                <p class="text-gray-500 text-center py-10">
                    No items found.
                </p>
            @endif

            <!-- LIST -->
            <div class="space-y-5">

                @foreach($items as $item)

                    <div class="group bg-white/70 backdrop-blur-md
                                rounded-2xl border border-white
                                shadow-sm hover:shadow-xl
                                transition transform hover:-translate-y-1">

                        <div class="flex gap-5 p-5">

                            <!-- IMAGE -->
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}"
                                     class="w-28 h-28 object-cover rounded-xl border border-gray-200 group-hover:scale-105 transition">
                            @else
                                <div class="w-28 h-28 flex items-center justify-center bg-gray-100 rounded-xl text-gray-500 text-sm">
                                    No Image
                                </div>
                            @endif

                            <!-- INFO -->
                            <div class="flex-1 space-y-1">

                                <h2 class="text-lg font-semibold text-gray-800 group-hover:text-emerald-700 transition">
                                    {{ $item->username }}
                                </h2>

                                <p class="text-sm text-gray-600">
                                    📞 {{ $item->phone }}
                                </p>

                                <p class="text-gray-700 leading-relaxed">
                                    {{ $item->description }}
                                </p>

                                <a href="{{ route('map') }}?location={{ urlencode($item->location) }}"
                                   class="hover:text-emerald-600 transition">
                                    Location 
                                </a>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

        <!-- RIGHT: FLOATING INFO PANEL -->
        <div class="md:col-span-1">

            <div class="sticky top-10">

                <div class="relative p-8 rounded-3xl
                            bg-white/40 backdrop-blur-xl
                            border border-white/40
                            shadow-2xl overflow-hidden">

                    <!-- soft glow -->
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-300/30 blur-3xl rounded-full animate-pulse"></div>
                    <div class="absolute -bottom-10 -left-10 w-52 h-52 bg-emerald-400/20 blur-3xl rounded-full animate-pulse"></div>

                    <div class="relative space-y-5">

                        <h2 class="text-3xl font-bold text-white leading-tight">
                            Help reunite lost items
                        </h2>

                        <p class="text-white text-lg leading-relaxed">
                            Every listing represents something meaningful to someone.
                            A small action from you can complete a big story.
                        </p>

                        <div class="space-y-2 text-sm text-white">
                            <p> Use keywords to filter quickly</p>
                            <p> Check location carefully</p>
                            <p> Contact owners when matched</p>
                        </div>

                        <a href="{{ route('lost-items.create') }}"
                           class="block text-center mt-6 px-5 py-3 bg-emerald-500 text-white rounded-xl font-medium hover:bg-emerald-600 transition transform hover:scale-[1.02]">
                            Report Lost Item
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection