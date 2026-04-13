@extends('layouts.app')

@section('content')
    <h1>Latest News</h1>

    <form method="GET" action="{{ route('news.index') }}">
        <div>
            <label for="sort">Sort by:</label>
            <select name="sort" id="sort">
                <option value="publishedAt" {{ $sortBy === 'publishedAt' ? 'selected' : '' }}>Newest</option>
                <option value="popularity"  {{ $sortBy === 'popularity'  ? 'selected' : '' }}>Oldest</option>
            </select>
        </div>

        <div>
            <label for="from">From:</label>
            <input type="date" name="from" id="from" value="{{ $from }}">
        </div>

        <div>
            <label for="to">To:</label>
            <input type="date" name="to" id="to" value="{{ $to }}">
        </div>

        <button type="submit">Filter</button>
        <a href="{{ route('news.index') }}">Reset</a>
    </form>

    @if(empty($articles))
        <p>No news articles found.</p>
    @else
        @foreach($articles as $article)
            <div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">
                @if($article['urlToImage'])
                    <img src="{{ $article['urlToImage'] }}" alt="thumbnail"
                         style="width: 100%; max-height: 200px; object-fit: cover;">
                @endif
                <h2>{{ $article['title'] }}</h2>
                <p>{{ $article['description'] }}</p>
                <small>
                    {{ $article['source']['name'] }} —
                    {{ \Carbon\Carbon::parse($article['publishedAt'])->format('M d, Y') }}
                </small>
                <br>
                <a href="{{ $article['url'] }}" target="_blank">Read more</a>
            </div>
        @endforeach
    @endif
@endsection