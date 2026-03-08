@extends('layouts.app')

@section('content')
    <h1>Announcements</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @forelse($announcements as $announcement)
        <div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">
            <h2>{{ $announcement->headline }}</h2>
            <p>{{ $announcement->content }}</p>
            <small>Posted on {{ $announcement->created_at->format('M d, Y') }}</small>
        </div>
    @empty
        <p>No announcements yet.</p>
    @endforelse

    {{ $announcements->links() }}
@endsection