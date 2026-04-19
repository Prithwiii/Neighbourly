@extends('layouts.app')

@section('content')

<div class="min-h-screen  py-10">

    <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row gap-8">

        <!-- LEFT SIDE: LIST -->
        <div class="md:w-2/3">

            <!-- HEADER -->
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                Lost Items
            </h1>

            <!-- SEARCH -->
            <form method="GET" action="{{ route('lost-items.search') }}"
                  class="flex gap-3 mb-8">

                <input type="text" name="query"
                    value="{{ request('query') }}"
                    placeholder="Search lost items..."
                    class="flex-1 p-3 rounded-xl bg-white border border-gray-200
                           focus:outline-none focus:ring-2 focus:ring-emerald-400"
                    required>

                <button class="px-5 py-3 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 transition">
                    Search
                </button>

            </form>

            <!-- SUCCESS -->
            @if(session('success'))
                <div class="mb-6 p-3 rounded-xl bg-green-100 text-green-700 text-center">
                    {{ session('success') }}
                </div>
            @endif

            <!-- EMPTY -->
            @if($items->isEmpty())
                <p class="text-gray-500 text-center">
                    No items found.
                </p>
            @endif

            <!-- GRID -->
            <div class="grid grid-cols-1 gap-6">

                @foreach($items as $item)

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition">

                        <div class="flex gap-5">

                            <!-- IMAGE -->
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}"
                                     class="w-28 h-28 object-cover rounded-xl border border-gray-200">
                            @else
                                <div class="w-28 h-28 flex items-center justify-center bg-gray-100 rounded-xl text-gray-500 text-sm">
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

        <!-- RIGHT SIDE: BIG WRITING PANEL -->
        <div class="md:w-1/3 md:sticky md:top-10 self-start">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">

                <h2 class="text-3xl font-bold text-emerald-700 leading-tight">
                    Help reunite lost items
                </h2>

                <p class="text-gray-600 mt-4 text-lg leading-relaxed">
                    Every item listed here represents someone’s lost belonging.
                    If you recognize something, reach out and help return it to its owner.
                </p>

                <div class="mt-6 space-y-3 text-sm text-gray-500">

                    <p>🔍 Search items using keywords</p>
                    <p>📍 Check location details carefully</p>
                    <p>🤝 Contact owners when you find matches</p>

                </div>

                <div class="mt-8">

                    <a href="{{ route('lost-items.create') }}"
                       class="block text-center px-5 py-3 bg-emerald-500 text-white rounded-xl font-medium hover:bg-emerald-600 transition">
                        Report Lost Item
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection