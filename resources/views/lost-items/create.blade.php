@extends('layouts.app')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center relative">

    <!-- GLASS CARD -->
    <div class="w-[550px] p-8 rounded-3xl
                bg-white/25 backdrop-blur-xl
                border border-white/30
                shadow-2xl">

        <h1 class="text-3xl font-bold text-emerald-700 mb-6 text-center">
            Report Lost Item
        </h1>

        <form method="POST" action="/lost-items" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Name -->
            <input type="text" name="username" placeholder="Your Name"
                class="w-full p-3 rounded-xl bg-white/60 border border-gray-200
                       focus:outline-none focus:ring-2 focus:ring-emerald-400"
                required>

            <!-- Phone -->
            <input type="text" name="phone" placeholder="Phone Number"
                class="w-full p-3 rounded-xl bg-white/60 border border-gray-200
                       focus:outline-none focus:ring-2 focus:ring-emerald-400"
                required>

            <!-- Description -->
            <textarea name="description" placeholder="Description"
                class="w-full p-3 rounded-xl bg-white/60 border border-gray-200
                       focus:outline-none focus:ring-2 focus:ring-emerald-400"
                required></textarea>

            <!-- Location -->
            <input type="text" name="location" placeholder="Location"
                class="w-full p-3 rounded-xl bg-white/60 border border-gray-200
                       focus:outline-none focus:ring-2 focus:ring-emerald-400"
                required>

            <!-- Date -->
            <input type="date" name="date_lost"
                class="w-full p-3 rounded-xl bg-white/60 border border-gray-200
                       focus:outline-none focus:ring-2 focus:ring-emerald-400"
                required>

            <!-- Image -->
            <input type="file" name="image"
                class="w-full p-3 rounded-xl bg-white/60 border border-gray-200">

            <!-- Button -->
            <button type="submit"
                class="w-full bg-emerald-600 text-white py-3 rounded-xl
                       hover:bg-emerald-700 transition shadow-md">
                Submit Report
            </button>

        </form>

    </div>

</div>

@endsection