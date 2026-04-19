@extends('layouts.app')

@section('content')
<h2>Available Jobs</h2>

@foreach($jobs as $job)
    <div style="border:1px solid #ccc; padding:10px; margin:10px;">
        <p><strong>{{ $job->name }}</strong></p>
        <p>{{ $job->location }}</p>
        <p>Status: {{ $job->status }}</p>

        <a href="{{ route('jobs.show', $job) }}">View</a>
    </div>
@endforeach
@endsection