@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto space-y-8">

    <!-- PAGE HEADER -->
    <div class="grid md:grid-cols-2 gap-8 items-center">

        <!-- LEFT: TEXT -->
        <div class="space-y-4">
            <h1 class="text-4xl md:text-5xl font-bold text-emerald-700">
                Stay Updated
            </h1>

            <p class="text-gray-700 text-lg">
                Discover what's happening around the world. Filter, explore, and stay informed with curated news.
            </p>
        </div>

        <!-- RIGHT: FILTER CARD -->
        <div class="p-6 rounded-3xl
                    bg-white/30 backdrop-blur-xl
                    border border-white/40 shadow-2xl">

            <form method="GET" action="{{ route('news.index') }}"
                  class="space-y-4">

                <div>
                    <label class="text-sm text-gray-700">Search</label>
                    <input type="text" name="query" value="{{ $query ?? '' }}"
                        placeholder="Search news..."
                        class="mt-1 w-full rounded-xl border-gray-300 px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">

                    <div>
                        <label class="text-sm text-gray-700">Sort</label>
                        <select name="sort"
                            class="mt-1 w-full rounded-xl border-gray-300 px-3 py-2">
                            <option value="publishedAt" {{ $sortBy === 'publishedAt' ? 'selected' : '' }}>Newest</option>
                            <option value="popularity"  {{ $sortBy === 'popularity'  ? 'selected' : '' }}>Popularity</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm text-gray-700">From</label>
                        <input type="date" name="from" value="{{ $from }}"
                            class="mt-1 w-full rounded-xl border-gray-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="text-sm text-gray-700">To</label>
                        <input type="date" name="to" value="{{ $to }}"
                            class="mt-1 w-full rounded-xl border-gray-300 px-3 py-2">
                    </div>

                </div>

                <div class="flex gap-3 pt-2">
                    <button class="flex-1 bg-emerald-600 text-white py-2 rounded-xl
                                   hover:bg-emerald-700 transition shadow-md">
                        Apply Filters
                    </button>

                    <a href="{{ route('news.index') }}"
                       class="px-4 py-2 rounded-xl bg-white/40 hover:bg-white/60
                              border border-white/30 text-gray-700 transition">
                        Reset
                    </a>
                </div>

            </form>
        </div>

    </div>


    <!-- NEWS SECTION -->
    @if(empty($articles))
        <div class="text-center text-gray-700 py-10">
            No news articles found.
        </div>
    @else

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 pb-10">

            @foreach($articles as $article)
                <div class="rounded-3xl overflow-hidden
                            bg-white/30 backdrop-blur-xl
                            border border-white/40 shadow-xl
                            hover:shadow-2xl hover:-translate-y-1 transition duration-300">

                    @if($article['urlToImage'])
                        <img src="{{ $article['urlToImage'] }}"
                             class="w-full h-44 object-cover">
                    @endif

                    <div class="p-5 flex flex-col h-full">

                        <h2 class="font-semibold text-lg text-gray-800 mb-2 leading-snug">
                            {{ $article['title'] }}
                        </h2>

                        <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                            {{ $article['description'] }}
                        </p>

                        <div class="text-xs text-gray-500 mb-4">
                            {{ $article['source']['name'] }} —
                            {{ \Carbon\Carbon::parse($article['publishedAt'])->format('M d, Y') }}
                        </div>

                        <div class="mt-auto">
                            <a href="{{ $article['url'] }}" target="_blank"
                               class="inline-block text-emerald-700 font-semibold
                                      hover:text-emerald-800 transition">
                                Read more →
                            </a>
                        </div>

                    </div>
                </div>
            @endforeach

        </div>

    @endif

</div>

@endsection