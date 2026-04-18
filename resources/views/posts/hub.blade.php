@extends('layouts.app')

@section('content')

<div class="flex items-center justify-center min-h-screen">

    <div class="w-[500px] p-10 rounded-3xl
                bg-white/20 backdrop-blur-xl
                border border-white/30 shadow-2xl">

        <h1 class="text-2xl font-bold text-center text-emerald-700 mb-6">
            Community Blog
        </h1>

        <div class="grid grid-cols-1 gap-5">

            <!-- OPTION 1 -->
            <a href="{{ route('posts.create') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      text-center shadow-md hover:shadow-xl transition">
                ✍️ Create a Post
            </a>

            <!-- OPTION 2 -->
            <a href="{{ route('posts.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      text-center shadow-md hover:shadow-xl transition">
                📜 View Community Posts
            </a>

        </div>

    </div>

</div>

@endsection