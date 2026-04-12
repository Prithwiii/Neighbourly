<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Neighbourly</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-gray-100 flex items-center justify-center min-h-screen">

<div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 text-center">

    <h2 class="text-2xl font-bold text-blue-600 mb-4">
        Verify Your Email 
    </h2>

    <p class="text-sm text-gray-600 mb-6">
        Thanks for signing up! Before getting started, please verify your email by clicking the link we sent you.
        If you didn’t receive it, you can resend it below.
    </p>

    <!-- Success message -->
    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-green-600 text-sm font-medium">
            A new verification link has been sent to your email.
        </div>
    @endif

    <!-- Resend email -->
    <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
        @csrf

        <button type="submit"
            class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
            Resend Verification Email
        </button>
    </form>

    <!-- Logout -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit"
            class="text-sm text-red-500 hover:underline">
            Log out
        </button>
    </form>

</div>

</body>
</html>