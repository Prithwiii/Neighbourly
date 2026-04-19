@extends('layouts.app')

@section('content')

<style>
    /* Simple fade + slide animation */
    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade {
        animation: fadeUp 0.8s ease-out both;
    }

    .float-slow {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-6px); }
    }
</style>

<div class="min-h-screen ">

    <div class="max-w-6xl mx-auto px-4 py-10 flex flex-col md:flex-row gap-10">

        <!-- LEFT SIDE (BIG + ANIMATED WRITING) -->
        <div class="md:w-1/3 md:sticky md:top-10 self-start ">

            <div class=" rounded-2xl border border-gray-100 shadow-sm p-8 text-center ">

                <h2 class="text-6xl font-bold text-white leading-tight">
                    Read <br> thoughts
                </h2>

                <p class="text-white mt-5 text-lg leading-relaxed">
                    Discover stories, ideas, and moments shared by people in your community.
                    Scroll through posts and explore what others are thinking.
                </p>

                <div class="mt-8">
                    <a href="{{ route('posts.create') }}"
                       class="inline-block px-7 py-3 bg-emerald-500 text-white rounded-full text-sm font-medium hover:bg-emerald-600 transition">
                        Share your thoughts
                    </a>
                </div>

            </div>

        </div>

        <!-- RIGHT SIDE (SCROLLING POSTS) -->
        <div class="md:w-2/3 space-y-6 ">

            
            @forelse($posts as $post)

                <div class="rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition">

                    <div class="flex flex-col md:flex-row gap-5">

                        <!-- TEXT -->
                        <div class="flex-1">

                            <h2 class="font-semibold text-white text-lg">
                                {{ $post->username }}
                            </h2>

                            <p class="text-white mt-3 leading-relaxed">
                                {{ $post->content }}
                            </p>

                            <div class="mt-4 flex items-center justify-between text-sm text-white">

                                <a href="{{ route('map') }}?location={{ urlencode($post->location) }}"
                                   class="hover:text-emerald-600 transition">
                                    Location {{ $post->location }}
                                </a>

                                <span>
                                    {{ $post->created_at->diffForHumans() }}
                                </span>

                            </div>

                        </div>

                        <!-- IMAGE -->
                        @if($post->image)
                            <div class="md:w-44 md:h-44 w-full h-56 flex-shrink-0">
                                <img src="{{ asset('storage/' . $post->image) }}"
                                     class="w-full h-full object-cover rounded-xl border border-gray-200">
                            </div>
                        @endif

                    </div>

                </div>

            @empty

                <div class="text-center text-gray-500 mt-10">
                    No posts yet.
                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection