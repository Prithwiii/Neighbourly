@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto p-6">

    <!-- HEADER -->
    <h1 class="text-3xl font-bold text-emerald-700 mb-6 text-center">
         Notifications
    </h1>

    @php
        auth()->user()->unreadNotifications->markAsRead();
    @endphp

    <!-- LIST -->
    <div class="space-y-4">

        @foreach(auth()->user()->notifications as $notification)

            <div class="p-5 rounded-2xl
                        bg-white/30 backdrop-blur-xl
                        border border-white/30
                        shadow-md hover:shadow-xl
                        transition">

                <!-- MESSAGE -->
                <p class="text-gray-800 font-medium">
                    🚨 {{ $notification->data['message'] }}
                </p>

                <!-- LOCATION BUTTON -->
                <div class="mt-3 flex justify-between items-center">

                    <a href="{{ route('map') }}?lat={{ $notification->data['lat'] }}&lng={{ $notification->data['lng'] }}"
                       class="text-sm text-emerald-700 font-semibold hover:underline">
                        📍 View Location on Map
                    </a>

                    <span class="text-xs text-gray-500">
                        {{ $notification->created_at->diffForHumans() }}
                    </span>

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection