@extends('layouts.app')

@section('content')
<h2>Create Enlisting</h2>

<form method="POST" action="{{ route('enlistings.store') }}">
    @csrf

    <input type="text" name="name" placeholder="Your Name"><br>
    <input type="text" name="contact" placeholder="Contact"><br>
    <input type="text" name="preferred_job" placeholder="Preferred Job"><br>

    <textarea name="availability" placeholder="Availability (days & hours)"></textarea><br>

    <button type="submit">Submit</button>
</form>
@endsection