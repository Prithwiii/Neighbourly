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
<<<<<<< HEAD
            <a href="{{ route('lost-items.create') }}">Submit Lost Item</a>
=======
            <a href="{{ route('announcements.index') }}">Announcements</a> |
            
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}">Admin Panel</a> |
            @endif
>>>>>>> fb4efa5e81ea071c47162cd0fc361cad66c82e02
            
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