@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center px-4">

    <!-- MAIN CONTAINER -->
    <div class="w-full max-w-5xl bg-white rounded-[30px] shadow-sm px-10 py-16 text-center">

        <!-- PROFILE / ICON -->
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 rounded-full bg-green-200 flex items-center justify-center text-xl font-semibold text-gray-600">
                Neighbourly
            </div>
        </div>

        <!-- TITLE -->
        <h1 class="text-4xl md:text-5xl font-semibold text-gray-800 leading-tight mb-4">
            Share your thoughts <br>
            with the community.
        </h1>

        <!-- SUBTEXT -->
        <p class="text-gray-500 max-w-xl mx-auto mb-10">
            Write, explore, and connect through meaningful stories and ideas.
        </p>

        <!-- BUTTONS -->
        <div class="flex justify-center gap-4 mb-12">

            <a href="{{ route('posts.create') }}"
               class="px-6 py-3 bg-emerald-400 text-black rounded-full text-sm font-medium hover:opacity-90 transition">
                Create Post
            </a>

            <a href="{{ route('posts.index') }}"
               class="px-6 py-3 border border-gray-300 rounded-full text-sm text-black hover:bg-gray-100 transition">
                Browse Posts
            </a>

        </div>

        

        </div>

    </div>

</div>

@endsection