<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Neighbourly</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen relative text-gray-800">

<!-- 🌍 BACKGROUND IMAGE -->
<div class="fixed inset-0 -z-10">
    <img src="{{ asset('images/bg.jpg') }}"
         class="w-full h-full object-cover">
</div>

<!-- 🌫 OVERLAY -->
<div class="fixed inset-0 -z-10 bg-black/20"></div>


<!-- CARD -->
<div class="flex items-center justify-center min-h-screen px-4">

    <div class="w-full max-w-md p-8 rounded-2xl
                bg-white/20 backdrop-blur-2xl
                border border-white/30
                shadow-2xl text-center">

        <!-- TITLE -->
        <h2 class="text-2xl font-bold text-emerald-700 mb-4">
            Forgot Password?
        </h2>

        <p class="text-sm text-white mb-6">
            No problem. Enter your email and we’ll send you a reset link.
        </p>

        <!-- STATUS -->
        @if (session('status'))
            <div class="mb-4 text-emerald-200 text-sm font-medium">
                {{ session('status') }}
            </div>
        @endif

        <!-- FORM -->
        <form method="POST" action="{{ route('password.email', absolute: false) }}" class="space-y-4">
            @csrf

            <!-- EMAIL -->
            <div class="text-left">
                <label class="text-sm font-medium text-white">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full mt-1 p-3 rounded-lg bg-white/80
                           focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                @error('email')
                    <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full bg-emerald-600 text-white py-3 rounded-lg
                       hover:bg-emerald-700 transition">
                Send Reset Link
            </button>

        </form>

        <!-- BACK -->
        <p class="text-sm mt-6">
            <a href="{{ route('login', absolute: false) }}" class="text-emerald-200 hover:underline">
                Back to Login
            </a>
        </p>

    </div>

</div>

</body>
</html>