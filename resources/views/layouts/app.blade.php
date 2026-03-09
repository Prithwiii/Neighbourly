<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Neighbourly</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <nav style="background:#eee; padding:10px;">
        <a href="{{ url('/') }}">Home</a> |
        @auth
            <a href="{{ route('dashboard') }}">Dashboard</a> |
            <a href="{{ route('lost-items.index') }}">Lost & Found</a> |
            <a href="{{ route('lost-items.create') }}">Submit Lost Item</a>
            
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}">Login</a> |
            <a href="{{ route('register') }}">Register</a>
        @endauth
    </nav>

    <div class="container" style="padding:20px;">
        @yield('content')
    </div>
</body>
</html>