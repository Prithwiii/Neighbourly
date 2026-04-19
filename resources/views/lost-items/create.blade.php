@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center px-10">

    <div class="w-full max-w-6xl mx-auto grid md:grid-cols-2 gap-16 items-center">

        <!-- LEFT SIDE: INFO / CONTEXT -->
        <div class="space-y-6 animate-fadeIn">

            <h1 class="text-6xl font-bold text-emerald-700 leading-tight">
                Report a Lost Item
            </h1>

            <p class="text-gray-700 text-lg leading-relaxed max-w-md">
                Fill in a few details to help others identify and return your item.
                The more accurate the description, the higher the chance of recovery.
            </p>

            <div class="space-y-2 text-sm text-black">
                <p> Be specific in description</p>
                <p> Add image if available</p>
                <p> Mention correct date lost</p>
            </div>

        </div>

        <!-- RIGHT SIDE: FLOATING FORM CARD -->
        <div class="relative">

            <!-- soft glow -->
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-300/30 blur-3xl rounded-full animate-pulse"></div>
            <div class="absolute -bottom-10 -left-10 w-52 h-52 bg-emerald-400/20 blur-3xl rounded-full animate-pulse"></div>

            <!-- FORM CARD -->
            <div class="relative p-8 rounded-3xl
                        bg-white/30 backdrop-blur-xl
                        border border-white/40
                        shadow-2xl">

                <h2 class="text-xl font-semibold text-center text-gray-700 mb-6">
                    Submit Details
                </h2>

                <form method="POST" action="/lost-items" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <input type="text" name="phone" placeholder="Phone Number"
                        class="w-full p-3 rounded-xl bg-white/60 border border-gray-200
                               focus:outline-none focus:ring-2 focus:ring-emerald-400"
                        required>

                    <textarea name="description" placeholder="Describe your lost item"
                        class="w-full p-3 rounded-xl bg-white/60 border border-gray-200
                               focus:outline-none focus:ring-2 focus:ring-emerald-400"
                        required></textarea>

                    <input type="date" name="date_lost"
                        class="w-full p-3 rounded-xl bg-white/60 border border-gray-200
                               focus:outline-none focus:ring-2 focus:ring-emerald-400"
                        required>

                    <input type="file" name="image"
                        class="w-full p-3 rounded-xl bg-white/60 border border-gray-200">

                    <button type="submit"
                        class="w-full bg-emerald-600 text-white py-3 rounded-xl
                               hover:bg-emerald-700 transition shadow-md transform hover:scale-[1.02]">
                        Submit Report
                    </button>

                </form>

            </div>
        </div>

    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.8s ease-out both;
}
</style>

@endsection