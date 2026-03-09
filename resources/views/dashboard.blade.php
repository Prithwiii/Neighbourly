@extends('layouts.app')

@section('content')
<h1>Welcome, {{ Auth::user()->name }}!</h1>

<div>
    <a href="{{ route('lost-items.index') }}">
        <button>Lost & Found</button>
    </a>
    <br><br>
    <a href="{{ route('announcements.index') }}">
        <button>Announcements</button>
    </a>
    <br><br>
    <a href="{{ route('lost-items.create') }}">
        <button>Submit Lost item</button>
    </a>
</div>
@endsection