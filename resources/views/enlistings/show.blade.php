@extends('layouts.app')

@section('content')
<h2>{{ $enlisting->name }}</h2>

<p>Contact: {{ $enlisting->contact }}</p>
<p>Preferred Job: {{ $enlisting->preferred_job }}</p>
<p>Availability: {{ $enlisting->availability }}</p>

@can('delete', $enlisting)
<form method="POST" action="{{ route('enlistings.destroy', $enlisting) }}">
    @csrf
    @method('DELETE')
    <button>Delete</button>
</form>
@endcan

@endsection