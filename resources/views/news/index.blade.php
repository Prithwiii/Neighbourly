@extends('layouts.app')

@section('content')

<!-- SCROLL FIX (overrides app.blade overflow-hidden) -->

{{-- <div class="h-[calc(100vh-7rem)] overflow-y-auto px-6"> --}}

```
<div class="max-w-6xl mx-auto">

    <!-- FILTER BAR -->
    <div class="mb-6 p-6 rounded-2xl
                bg-white/20 backdrop-blur-xl
                border border-white/30 shadow-xl">

        <form method="GET" action="{{ route('news.index') }}"
              class="flex flex-wrap gap-4 items-end justify-between">

            <div>
                <label class="text-sm text-gray-700">Sort</label>
                <select name="sort"
                    class="block mt-1 rounded-lg border-gray-300 px-3 py-2">
                    <option value="publishedAt" {{ $sortBy === 'publishedAt' ? 'selected' : '' }}>Newest</option>
                    <option value="popularity"  {{ $sortBy === 'popularity'  ? 'selected' : '' }}>Popularity</option>
                </select>
            </div>

            <div>
                <label class="text-sm text-gray-700">From</label>
                <input type="date" name="from" value="{{ $from }}"
                    class="block mt-1 rounded-lg border-gray-300 px-3 py-2">
            </div>

            <div>
                <label class="text-sm text-gray-700">To</label>
                <input type="date" name="to" value="{{ $to }}"
                    class="block mt-1 rounded-lg border-gray-300 px-3 py-2">
            </div>

            <div class="flex gap-2">
                <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700">
                    Apply
                </button>

                <a href="{{ route('news.index') }}"
                   class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">
                    Reset
                </a>
            </div>

        </form>
    </div>

    {{-- <!-- TABS -->
    @php
        $categories = ['all', 'technology', 'business', 'sports', 'health'];
        $activeTab = request('category', 'all');
    @endphp

    <div class="mb-6 flex gap-3 flex-wrap">
        @foreach($categories as $cat)
            <a href="{{ route('news.index', array_merge(request()->all(), ['category' => $cat])) }}"
               class="px-4 py-2 rounded-full text-sm
               {{ $activeTab === $cat
                    ? 'bg-emerald-600 text-white'
                    : 'bg-white/30 text-gray-700 hover:bg-white/40' }}
               backdrop-blur-md border border-white/30 shadow">
                {{ ucfirst($cat) }}
            </a>
        @endforeach
    </div> --}}

    <!-- NEWS GRID -->
    @if(empty($articles))
        <div class="text-center text-gray-700">
            No news articles found.
        </div>
    @else

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 pb-10">

            @foreach($articles as $article)
                <div class="rounded-2xl overflow-hidden
                            bg-white/20 backdrop-blur-xl
                            border border-white/30 shadow-xl
                            hover:shadow-2xl transition">

                    @if($article['urlToImage'])
                        <img src="{{ $article['urlToImage'] }}"
                             class="w-full h-40 object-cover">
                    @endif

                    <div class="p-4">

                        <h2 class="font-semibold text-lg mb-2">
                            {{ $article['title'] }}
                        </h2>

                        <p class="text-sm text-gray-700 mb-3">
                            {{ $article['description'] }}
                        </p>

                        <div class="text-xs text-gray-600 mb-3">
                            {{ $article['source']['name'] }} —
                            {{ \Carbon\Carbon::parse($article['publishedAt'])->format('M d, Y') }}
                        </div>

                        <a href="{{ $article['url'] }}" target="_blank"
                           class="text-emerald-700 font-semibold hover:underline">
                            Read more →
                        </a>

                    </div>
                </div>
            @endforeach

        </div>

    @endif

</div>
```

</div>

@endsection
