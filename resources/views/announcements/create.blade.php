@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto px-6">

    <!-- CARD -->
    <div class="bg-white/30 backdrop-blur-xl border border-white/40 shadow-xl rounded-2xl p-8">

        <!-- TITLE -->
        <h2 class="text-3xl font-bold text-emerald-700 mb-6 text-center">
            📢 Create Announcement
        </h2>

        <!-- FORM -->
        <form method="POST" action="{{ route('announcements.store') }}" class="space-y-5">
            @csrf

            <!-- HEADLINE -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Headline
                </label>
                <input type="text" name="headline"
                    class="w-full p-3 rounded-lg border border-gray-300
                           focus:ring-2 focus:ring-emerald-400
                           focus:outline-none bg-white/70"
                    placeholder="Enter announcement title"
                    required>
            </div>

            <!-- CONTENT -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Content
                </label>
                <textarea name="content" rows="5"
                    class="w-full p-3 rounded-lg border border-gray-300
                           focus:ring-2 focus:ring-emerald-400
                           focus:outline-none bg-white/70"
                    placeholder="Write your announcement..."
                    required></textarea>
            </div>

            <!-- BUTTONS -->
            <div class="flex justify-between items-center mt-6">

                <a href="{{ route('announcements.index') }}"
                   class="text-gray-600 hover:text-emerald-700 transition">
                    ← Back
                </a>

                <button type="submit"
                    class="bg-emerald-500 text-white px-6 py-2 rounded-lg
                           shadow-md hover:bg-emerald-600
                           hover:scale-105 transition duration-200">
                    ➕ Publish
                </button>

            </div>
        </form>

        @error('headline')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

        @error('content')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
        </div>
        @endif

    </div>
</div>

@endsection