@extends('layouts.app')

@section('content')

<div x-data="{ showToast: false }"
     x-init="showToast = {{ session('success') ? 'true' : 'false' }};
             if(showToast) setTimeout(() => showToast = false, 3000);"
     class="max-w-5xl mx-auto grid md:grid-cols-2 gap-10 items-center">

    <!-- SUCCESS TOAST -->
    <div x-show="showToast"
         x-transition
         class="fixed top-5 right-5 bg-emerald-600 text-white px-5 py-3 rounded-xl shadow-lg z-50">
        {{ session('success') }}
    </div>

    <!-- LEFT -->
    <div class="space-y-5">
        <h1 class="text-4xl md:text-5xl font-bold text-emerald-700">
            Create an Announcement
        </h1>

        <p class="text-gray-700 text-lg">
            Share important updates with your community. Keep everyone informed and connected.
        </p>

        <p class="text-sm text-gray-500">
            Only admins can publish announcements.
        </p>
    </div>

    <!-- RIGHT -->
    <div class="p-8 rounded-3xl bg-white/30 backdrop-blur-xl border border-white/40 shadow-2xl">

        <form method="POST" action="{{ route('announcements.store') }}" class="space-y-5">
            @csrf

            <!-- HEADLINE -->
            <div>
                <label class="block text-sm text-gray-700 mb-1">Headline</label>

                <input type="text" name="headline"
                    value="{{ old('headline') }}"
                    placeholder="Enter announcement headline..."
                    class="w-full rounded-xl border px-4 py-2 focus:ring-emerald-500 focus:border-emerald-500">

                @error('headline')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- CONTENT -->
            <div>
                <label class="block text-sm text-gray-700 mb-1">Content</label>

                <textarea name="content" rows="5"
                    placeholder="Write your announcement..."
                    class="w-full rounded-xl border px-4 py-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('content') }}</textarea>

                @error('content')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- BUTTON -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-emerald-600 text-white py-2.5 rounded-xl hover:bg-emerald-700 transition shadow-md">
                    Publish Announcement
                </button>
            </div>

        </form>

    </div>
</div>

<!-- Alpine.js (needed for toast) -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@endsection