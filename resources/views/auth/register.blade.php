<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Neighbourly</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen relative text-gray-800">

<!-- 🌍 GLOBAL BACKGROUND -->
<div class="fixed inset-0 -z-10">
    <img src="{{ asset('images/bg.jpg') }}"
         class="w-full h-full object-cover">
</div>

<!-- 🌫 OVERLAY -->
<div class="fixed inset-0 -z-10 bg-black/20"></div>


<!-- REGISTER CARD -->
<div class="flex items-center justify-center min-h-screen px-4">

    <div class="w-full max-w-md p-8 rounded-2xl
                bg-white/20 backdrop-blur-2xl
                border border-white/30
                shadow-2xl">

        <!-- TITLE -->
        <h2 class="text-3xl font-bold text-center text-emerald-700 mb-6">
            Create Account
        </h2>

        <!-- FORM -->
        <form method="POST" action="{{ route('register', absolute: false) }}" class="space-y-5">
            @csrf

            <!-- NAME -->
            <div>
                <label class="text-sm font-medium text-white">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full mt-1 p-3 rounded-lg bg-white/80
                           focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                @error('name')
                    <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- EMAIL -->
            <div>
                <label class="text-sm font-medium text-white">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
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

            <!-- CONFIRM PASSWORD -->
            <div>
                <label class="text-sm font-medium text-white">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full mt-1 p-3 rounded-lg bg-white/80
                           focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full bg-emerald-600 text-white py-3 rounded-lg
                       hover:bg-emerald-700 transition">
                Register
            </button>

        </form>

        <!-- LOGIN LINK -->
        <p class="text-center text-sm mt-6 text-white">
            Already have an account?
            <a href="{{ route('login', absolute: false) }}" class="text-emerald-200 font-semibold hover:underline">
                Login
            </a>
        </p>

    </div>

</div>

</body>
</html>