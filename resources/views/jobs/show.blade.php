@extends('layouts.app')

@section('content')
<h2>Job Details</h2>

<p>Name: {{ $job->name }}</p>
<p>Contact: {{ $job->contact }}</p>
<p>Date: {{ $job->job_datetime }}</p>
<p>Location: {{ $job->location }}</p>
<p>Description: {{ $job->description }}</p>
<p>Status: {{ $job->status }}</p>

<hr>

@php
    $confirmation = $job->confirmations->first();
@endphp

<p>Employer Confirmed: {{ $confirmation && $confirmation->employer_confirmed ? 'Yes' : 'No' }}</p>
<p>Worker Confirmed: {{ $confirmation && $confirmation->worker_confirmed ? 'Yes' : 'No' }}</p>

<hr>

{{-- Employer confirm --}}
@if(auth()->id() === $job->user_id && (!$confirmation || !$confirmation->employer_confirmed))
<form method="POST" action="{{ route('jobs.confirm', $job) }}">
    @csrf
    <input type="hidden" name="enlisting_id" value="{{ $confirmation->enlisting_id ?? '' }}">
    <button>Confirm as Employer</button>
</form>
@endif

{{-- Worker confirm --}}
@foreach(\App\Models\Enlisting::where('user_id', auth()->id())->get() as $myEnlisting)

    <form method="POST" action="{{ route('jobs.confirm', $job) }}">
        @csrf
        <input type="hidden" name="enlisting_id" value="{{ $myEnlisting->id }}">
        <button>
            Confirm as Worker ({{ $myEnlisting->preferred_job }})
        </button>
    </form>

@endforeach

<hr>

{{-- Delete --}}
@if(auth()->id() === $job->user_id || auth()->user()->is_admin)
<form method="POST" action="{{ route('jobs.destroy', $job) }}">
    @csrf
    @method('DELETE')
    <button>Delete Job</button>
</form>
@endif

@endsection