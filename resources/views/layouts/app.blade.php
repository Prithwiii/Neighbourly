<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neighbourly</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fancy font -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>

@php
    $mode = session('ui_mode', 'hub');
@endphp

<<<<<<< HEAD
<!-- {{-- <body class="h-screen overflow-hidden bg-emerald-50 text-gray-800"> --}} -->
<body class="min-h-screen overflow-y-auto bg-emerald-50 text-gray-800">    
=======
<body class="min-h-screen overflow-x-hidden overflow-y-auto bg-emerald-50 text-gray-800">
>>>>>>> e20a5efc7ef416956768205d8a5fb77ba9712b33

<!-- 🌍 GLOBAL BACKGROUND IMAGE -->
<div class="fixed inset-0 -z-10">
    <img src="{{ asset('images/bg.jpg') }}"
         class="w-full h-full object-cover">
</div>

<!-- 🌫 OVERLAY (makes UI readable) -->
<div class="fixed inset-0 -z-10 bg-black/20"></div>


<!-- NAVBAR -->
<nav class="fixed top-0 left-0 w-full z-50
            bg-white/20 backdrop-blur-2xl
            border-b border-white/30
            shadow-lg">

    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between relative">

        <!-- FLOATING LOGO -->
        <div class="absolute left-6 -bottom-10">
            <div class="w-20 h-20 rounded-full
                        bg-emerald-500
                        shadow-2xl
                        flex items-center justify-center
                        border-4 border-white
                        hover:scale-110 transition duration-300">

                <span class="text-white text-2xl font-bold"
                      style="font-family: 'Pacifico', cursive;">
                    N
                </span>
            </div>
        </div>

        <!-- BRAND NAME -->
        <div class="ml-24">
            <h1 class="text-2xl font-semibold text-emerald-700 tracking-wide">
                Neighbourly
            </h1>
        </div>

        <!-- RIGHT SIDE -->
        <div class="space-x-4 text-sm flex items-center">

            @auth

                @if($mode === 'hub')

                    <a href="{{ route('set.app') }}"
                       class="text-emerald-700 font-semibold hover:underline">
                        Home
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600">
                            Logout
                        </button>
                    </form>

                @else

                    <a href="{{ route('set.hub') }}"
                       class="text-emerald-700 font-semibold hover:underline">
                        Home
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600">
                            Logout
                        </button>
                    </form>

                @endif

            @else

                <a href="{{ route('login') }}" class="hover:text-emerald-600">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="bg-emerald-600 text-white px-3 py-1 rounded-lg hover:bg-emerald-700">
                    Register
                </a>

            @endauth

        </div>

    </div>
</nav>


<!-- PAGE CONTENT -->
<main class="pt-28 relative z-10 min-h-screen pb-10">
    @yield('content')
</main>

</body>
</html>