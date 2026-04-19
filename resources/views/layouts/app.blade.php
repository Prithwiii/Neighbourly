<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neighbourly</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <style>
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-down {
            animation: fadeDown 0.6s ease-out;
        }
    </style>
</head>

@php
    $mode = session('ui_mode', 'hub');
@endphp

<body class="min-h-screen overflow-x-hidden bg-emerald-50 text-gray-800">

<!-- 🌍 BACKGROUND -->
<div class="fixed inset-0 -z-10">
    <img src="{{ asset('images/bg.jpg') }}"
         class="w-full h-full object-cover scale-105">
</div>

<!-- 🌫 CLEAN OVERLAY -->
<div class="fixed inset-0 -z-10 bg-black/30"></div>

<!-- NAVBAR -->
<nav class="fixed top-0 left-0 w-full z-50 fade-down
            bg-white/10 backdrop-blur-3xl
            border-b border-white/20
            shadow-lg">

    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <!-- LOGO (UPGRADED) -->
        <div class="flex items-center gap-3">

            <div class="w-14 h-14 rounded-2xl
                        bg-gradient-to-br from-emerald-400 to-emerald-600
                        shadow-xl flex items-center justify-center
                        border border-white/30
                        hover:scale-105 transition">

                <span class="text-white text-2xl font-bold"
                      style="font-family: 'Pacifico', cursive;">
                    N
                </span>

            </div>

            <div>
                <h1 class="text-xl font-semibold text-white tracking-wide">
                    Neighbourly
                </h1>
                
            </div>

        </div>

        <!-- NAV LINKS -->
        <div class="space-x-5 text-sm flex items-center text-white">

            @auth

                @if($mode === 'hub')

                    <a href="{{ route('home') }}" class="hover:text-emerald-200 text-xl transition">
                        Home
                    </a>

                @else

                    <a href="{{ route('home') }}" class="hover:text-emerald-200 text-xl transition">
                        Home
                    </a>

                @endif

                <a href="{{ route('notifications') }}" class="relative hover:text-emerald-200 text-xl transition">

                    Notifications

                    @if($unreadCount > 0)
                        <span class="absolute -top-2 -right-2 min-w-[18px] h-[18px] flex items-center justify-center bg-red-500 text-white text-[10px] rounded-full">
                            {{ $unreadCount }}
                        </span>
                    @endif

                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="bg-red-500/80 hover:bg-red-600 text-white px-3 py-1 rounded-lg transition">
                        Logout
                    </button>
                </form>

            @else

                <a href="{{ route('login') }}" class="hover:text-emerald-200 transition">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1 rounded-lg transition">
                    Register
                </a>

            @endauth

        </div>

    </div>
</nav>

<!-- CONTENT -->
<main class="pt-28 relative z-10 min-h-screen pb-10">

    @yield('content')

</main>

</body>
</html>