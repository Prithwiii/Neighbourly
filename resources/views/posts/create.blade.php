@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto py-10">

    <h1 class="text-2xl font-bold text-emerald-700 mb-6 text-center">
        Create Post
    </h1>

    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data"
          class="p-5 rounded-2xl bg-white/30 backdrop-blur-xl border">

        @csrf

        <input type="text" name="username"
               placeholder="Your name"
               class="w-full mb-3 p-3 rounded-lg border" required>

        <textarea name="content"
                  placeholder="Write something..."
                  class="w-full mb-3 p-3 rounded-lg border" required></textarea>

        <input type="text" name="location"
               placeholder="Location (click map later)"
               class="w-full mb-3 p-3 rounded-lg border">

        <input type="file" name="image" class="mb-3">

        <button class="bg-emerald-600 text-white px-5 py-2 rounded-lg">
            Post
        </button>

    </form>

</div>

@endsection