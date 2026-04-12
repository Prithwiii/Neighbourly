@extends('layouts.app')

@section('content')

<div class="min-h-[80vh] py-10">

    <div class="max-w-6xl mx-auto">

        <!-- HEADER -->
        <h1 class="text-3xl font-bold text-emerald-700 mb-6 text-center">
            Lost Items
        </h1>

        <!-- SEARCH BAR (GLASS STYLE) -->
        <form method="GET" action="{{ route('lost-items.search') }}"
              class="flex gap-3 mb-8 max-w-2xl mx-auto">

            <input type="text" name="query"
                value="{{ request('query') }}"
                placeholder="Search lost items..."
                class="flex-1 p-3 rounded-xl
                       bg-white/40 backdrop-blur-md
                       border border-white/30
                       focus:outline-none focus:ring-2 focus:ring-emerald-400"
                required>

            <button class="px-5 py-3 rounded-xl
                           bg-emerald-600 text-white
                           hover:bg-emerald-700 transition">
                Search
            </button>
        </form>

        <!-- SUCCESS MESSAGE -->
        @if(session('success'))
            <div class="max-w-2xl mx-auto mb-6 p-3 rounded-xl
                        bg-green-100 text-green-700 text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- EMPTY STATE -->
        @if($items->isEmpty())
            <p class="text-center text-gray-500">
                No items found.
            </p>
        @endif

        <!-- ITEMS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            @foreach($items as $item)

                <div class="rounded-2xl p-5
                            bg-white/30 backdrop-blur-xl
                            border border-white/30
                            shadow-lg hover:shadow-2xl
                            transition">

                    <div class="flex gap-4">

                        <!-- IMAGE -->
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}"
                                 class="w-28 h-28 md:w-32 md:h-32
                                        object-cover rounded-xl border border-white/30">
                        @else
                            <div class="w-28 h-28 md:w-32 md:h-32
                                        flex items-center justify-center
                                        bg-white/40 rounded-xl text-gray-500 text-sm">
                                No Image
                            </div>
                        @endif

                        <!-- INFO -->
                        <div class="flex-1">

                            <h2 class="text-lg font-semibold text-gray-800">
                                {{ $item->username }}
                            </h2>

                            <p class="text-sm text-gray-600 mt-1">
                                📞 {{ $item->phone }}
                            </p>

                            <p class="text-gray-700 mt-2">
                                {{ $item->description }}
                            </p>

                            <p class="text-xs text-gray-500 mt-3">
                                📍 {{ $item->location }} • {{ $item->date_lost }}
                            </p>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>

@endsection