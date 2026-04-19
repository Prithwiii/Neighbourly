@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center px-4 ">

    <!-- GLASS CARD -->
    <div class="w-full max-w-xl p-8 rounded-3xl
                bg-white/30 backdrop-blur-xl
                border border-white/40
                shadow-2xl">

        <h1 class="text-3xl font-bold text-emerald-700 mb-6 text-center">
            Report Lost Item
        </h1>

        <form method="POST" action="/lost-items" enctype="multipart/form-data" class="space-y-4">
            @csrf

            

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