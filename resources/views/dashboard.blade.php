@extends('layouts.app')

@section('content')
<h1>Welcome, {{ Auth::user()->name }}!</h1>

<div>
    <a href="{{ route('lost-items.index') }}">
        <button>Lost & Found</button>
    </a>
</div>
@endsection