@extends('layouts.app')

@section('content')
<h2>Post a Job</h2>

<form method="POST" action="{{ route('jobs.store') }}">
    @csrf

    <input type="text" name="name" placeholder="Your Name"><br>
    <input type="text" name="contact" placeholder="Contact"><br>
    <input type="datetime-local" name="job_datetime"><br>
    <input type="text" name="location" placeholder="Location"><br>

    <textarea name="description" placeholder="Job Description"></textarea><br>

    <button type="submit">Post Job</button>
</form>
@endsection