@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-4">Notifications</h1>

    @foreach(auth()->user()->notifications as $notification)
        <div class="p-3 bg-red-100 rounded mb-2">
            🚨 {{ $notification->data['message'] }}
        </div>
    @endforeach

</div>

@endsection