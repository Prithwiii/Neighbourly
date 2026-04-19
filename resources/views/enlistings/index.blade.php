@extends('layouts.app')

@section('content')
<h2>Available Workers</h2>

@foreach($enlistings as $e)
    <div style="border:1px solid #ccc; padding:10px; margin:10px;">
        <p><strong>{{ $e->name }}</strong></p>
        <p>{{ $e->preferred_job }}</p>
        <p>{{ $e->availability }}</p>

        <a href="{{ route('enlistings.show', $e) }}">View</a>
    </div>
@endforeach
@endsection