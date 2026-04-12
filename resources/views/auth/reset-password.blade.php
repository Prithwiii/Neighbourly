<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Neighbourly</title>

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


<!-- RESET CARD -->
<div class="flex items-center justify-center min-h-screen px-4">

    <div class="w-full max-w-md p-8 rounded-2xl
                bg-white/20 backdrop-blur-2xl
                border border-white/30
                shadow-2xl">

        <!-- TITLE -->
        <h2 class="text-2xl font-bold text-center text-emerald-700 mb-6">
            Reset Password
        </h2>

        <!-- FORM -->
        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf

            <!-- TOKEN -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- EMAIL -->
            <div>
                <label class="text-sm font-medium text-white">Email</label>
                <input type="email" name="email"
                       value="{{ old('email', $request->email) }}"
                       required autofocus
                       class="w-full mt-1 p-3 rounded-lg bg-white/80
                              focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                @error('email')
                    <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="text-sm font-medium text-white">New Password</label>
                <input type="password" name="password" required
                       class="w-full mt-1 p-3 rounded-lg bg-white/80
                              focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                @error('password')
                    <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- CONFIRM -->
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
                Reset Password
            </button>

        </form>

    </div>

</div>

</body>
</html>