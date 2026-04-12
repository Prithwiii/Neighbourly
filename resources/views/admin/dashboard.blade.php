@extends('layouts.app')

@section('content')
    <h1>Admin Dashboard</h1>
    <p>Welcome, admin! This is where you can manage site features.</p>
    <!-- Add admin-specific links or content here -->
    <div>
        <h2>Announcements Posts</h2>
        <a href="{{ route('announcements.create') }}">
            <button>Create Announcement</button>
        </a>
    </div>
    <br>
    <div>
        <h2>Service Providers</h2>
        <a href="{{ route('admin.providers.index') }}">
            <button>Review Provider Applications</button>
        </a>
    </div>
@endsection