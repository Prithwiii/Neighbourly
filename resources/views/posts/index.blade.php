@extends('layouts.app')

@section('content')

<div class="min-h-[80vh] py-10">

    <div class="max-w-4xl mx-auto">

        <!-- TITLE -->
        <h1 class="text-3xl font-bold text-emerald-700 text-center mb-6">
            Community Blog
        </h1>

        

        <!-- POSTS -->
        @forelse($posts as $post)

            <div class="mb-6 p-5 rounded-2xl bg-white/30 backdrop-blur-xl border shadow">

                <h2 class="font-semibold text-lg text-gray-800">
                    {{ $post->username }}
                </h2>

                <p class="text-gray-700 mt-2">
                    {{ $post->content }}
                </p>

                @if($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}"
                         class="mt-3 rounded-lg max-h-60 object-cover">
                @endif

                <p class="text-xs text-gray-500 mt-3">
                    <a href="{{ route('map') }}?location={{ urlencode($post->location) }}"
                        class="text-blue-600 underline">
                       📍 {{ $post->location }}
                     </a>
                     • {{ $post->created_at->diffForHumans() }}
                </p>

            </div>

        @empty
            <p class="text-center text-gray-500">
                No posts yet.
            </p>
        @endforelse

    </div>

</div>

@endsection