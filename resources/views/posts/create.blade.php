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

<div class="min-h-screen  flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-2xl animate-fade">

        <!-- HEADER -->
        <div class="text-center mb-8">

            <h1 class="text-3xl md:text-4xl font-bold text-gray-800">
                Share your thoughts
            </h1>

            <p class="text-gray-500 mt-2">
                Write something meaningful and publish it to your community.
            </p>

        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data"
              class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 space-y-4">

            @csrf

            <!-- CONTENT -->
            <textarea name="content"
                      placeholder="What's on your mind?"
                      rows="6"
                      class="w-full p-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-400 outline-none resize-none"
                      required></textarea>

            <!-- IMAGE -->
            <div class="border border-gray-200 rounded-xl p-3">
                <input type="file" name="image" class="w-full text-sm text-gray-600">
            </div>

            <!-- BUTTON -->
            <button type="submit"
                    class="w-full bg-emerald-500 text-white py-3 rounded-xl font-medium hover:bg-emerald-600 transition">
                Post
            </button>

        </form>

    </div>

</div>

@endsection