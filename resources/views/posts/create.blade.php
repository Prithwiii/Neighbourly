@extends('layouts.app')

@section('content')

<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade {
        animation: fadeUp 0.7s ease-out both;
    }
</style>

<div class="min-h-screen flex items-center justify-center px-6 py-12">

    <div class="w-full max-w-3xl animate-fade">

        <!-- HEADER (OUTSIDE FEEL, CLEAN + WHITE STYLE) -->
        <div class="text-center mb-10">

            <h1 class="text-4xl md:text-5xl font-semibold text-white">
                Share your thoughts
            </h1>

            <p class="text-white/70 mt-3 text-lg">
                Write something meaningful and publish it to your community.
            </p>

        </div>

        <!-- FORM CARD (LIGHT GLASS OVER YOUR BACKGROUND IMAGE) -->
        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data"
              class="bg-white/10 backdrop-blur-xl
                     border border-white/10
                     rounded-3xl shadow-2xl
                     p-8 space-y-5">

            @csrf

            <!-- TEXTAREA -->
            <textarea name="content"
                      placeholder="What's on your mind?"
                      rows="6"
                      class="w-full p-4 rounded-2xl
                             bg-white/5 text-white placeholder-white/40
                             border border-white/10
                             focus:ring-2 focus:ring-emerald-400 outline-none resize-none"
                      required></textarea>

            <!-- IMAGE -->
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <input type="file"
                       name="image"
                       class="w-full text-sm text-white/70 file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:bg-white/10 file:text-white
                              hover:file:bg-white/20">
            </div>

            <!-- BUTTON -->
            <button type="submit"
                    class="w-full bg-emerald-500 text-black py-3 rounded-2xl font-medium
                           hover:bg-emerald-400 transition transform hover:scale-[1.02]">
                Post
            </button>

        </form>

    </div>

</div>

@endsection