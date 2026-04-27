@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center px-6  from-gray-950 via-gray-900 to-gray-950">

    <div class="w-full max-w-6xl grid md:grid-cols-2 gap-16 items-center">

        <!-- LEFT: WRITING -->
        <div class="space-y-6 animate-fadeIn">

           

            <h1 class="text-6xl font-semibold text-white leading-tight">
                Share your thoughts<br>
                with the community.
            </h1>

            <p class="text-white/70 text-lg max-w-md leading-relaxed">
                Write, explore, and connect through meaningful stories and ideas.
                A simple space for real expression.
            </p>

            <div class="space-y-2 text-sm text-white/50">
                <p> Create meaningful posts</p>
                <p> Explore community ideas</p>
                <p> Connect through stories</p>
            </div>

        </div>

        <!-- RIGHT: ACTION PANEL -->
        <div class="relative">

            <!-- glow background -->
            <div class="absolute -top-16 -right-10 w-72 h-72 bg-emerald-500/20 blur-3xl rounded-full"></div>
            <div class="absolute -bottom-16 -left-10 w-72 h-72 bg-white/10 blur-3xl rounded-full"></div>

            <!-- CARD -->
            <div class="relative p-10 rounded-3xl
                        bg-white/5 backdrop-blur-xl
                        border border-white/10
                        shadow-2xl text-center space-y-6">

                <h2 class="text-xl text-white font-medium">
                    Choose your path
                </h2>

                <!-- CREATE -->
                <a href="{{ route('posts.create') }}"
                   class="block px-6 py-4 rounded-2xl
                          bg-emerald-500/90 hover:bg-emerald-400
                          text-black font-medium
                          transition transform hover:scale-105">

                    Create Post
                </a>

                <!-- BROWSE -->
                <a href="{{ route('posts.index') }}"
                   class="block px-6 py-4 rounded-2xl
                          border border-white/20
                          text-white hover:bg-white/10
                          transition">

                    Browse Posts
                </a>

            </div>

        </div>

    </div>

</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(14px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.8s ease-out both;
}
</style>

@endsection