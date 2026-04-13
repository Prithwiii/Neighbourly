<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Neighbourly</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen relative text-gray-800">

<!-- 🌍 GLOBAL BACKGROUND (same as app) -->
<div class="fixed inset-0 -z-10">
    <img src="{{ asset('images/bg.jpg') }}"
         class="w-full h-full object-cover">
</div>

<!-- 🌫 SOFT OVERLAY -->
<div class="fixed inset-0 -z-10 bg-black/20"></div>


<!-- LOGIN CARD -->
<div class="flex items-center justify-center min-h-screen px-4">

    <div class="w-full max-w-md p-8 rounded-2xl
                bg-white/20 backdrop-blur-2xl
                border border-white/30
                shadow-2xl">

        <!-- TITLE -->
        <h2 class="text-3xl font-bold text-center text-emerald-700 mb-6">
            Neighbourly
        </h2>

        <!-- STATUS -->
        @if(session('status'))
            <div class="mb-4 text-green-600 text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        <!-- FORM -->
        <form method="POST" action="{{ route('login', absolute: false) }}" class="space-y-5">
            @csrf

            <!-- EMAIL -->
            <div>
                <label class="text-sm font-medium text-white">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full mt-1 p-3 rounded-lg bg-white/80
                           focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                @error('email')
                    <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="text-sm font-medium text-white">Password</label>
                <input type="password" name="password" required
                    class="w-full mt-1 p-3 rounded-lg bg-white/80
                           focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                @error('password')
                    <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- OPTIONS -->
            <div class="flex items-center justify-between text-sm text-white">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    Remember me
                </label>

                @if(Route::has('password.request'))
                    <a href="{{ route('password.request', absolute: false) }}" class="text-emerald-200 hover:underline">
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full bg-emerald-600 text-white py-3 rounded-lg
                       hover:bg-emerald-700 transition">
                Login
            </button>

        </form>

        <!-- REGISTER -->
        <p class="text-center text-sm mt-6 text-white">
            Don’t have an account?
            <a href="{{ route('register', absolute: false) }}" class="text-emerald-200 font-semibold hover:underline">
                Register
            </a>
        </p>

    </div>

</div>

</body>
</html>