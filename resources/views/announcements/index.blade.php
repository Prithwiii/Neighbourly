@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8 space-y-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between p-4 rounded-2xl
                bg-white/20 backdrop-blur-xl border border-white/30 shadow-md">

        <h1 class="text-2xl font-semibold text-emerald-700">
            Announcements
        </h1>

        @if(auth()->user()->isAdmin())
            <a href="{{ route('announcements.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                      bg-emerald-600 text-white text-sm font-medium
                      hover:bg-emerald-700 transition shadow">
                ➕ Post Announcement
            </a>
        @endif
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-100 text-emerald-700
                    border border-emerald-300 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- ANNOUNCEMENTS LIST -->
    <div class="space-y-5">

        @forelse($announcements as $announcement)

            <div class="p-6 rounded-2xl
                        bg-white/20 backdrop-blur-xl
                        border border-white/30 shadow-lg
                        hover:shadow-xl transition">

                <!-- TITLE -->
                <h2 class="text-xl font-semibold text-gray-800 mb-2">
                    {{ $announcement->headline }}
                </h2>

                <!-- CONTENT -->
                <p class="text-gray-700 leading-relaxed mb-4">
                    {{ $announcement->content }}
                </p>

                <!-- META -->
                <div class="text-xs text-gray-500 flex justify-between items-center">
                    <span>Posted on {{ $announcement->created_at->format('M d, Y') }}</span>
                </div>

            </div>

        @empty

            <div class="text-center py-10 text-gray-500">
                No announcements yet.
            </div>

        @endforelse

    </div>

    <!-- PAGINATION -->
    <div class="pt-4">
        {{ $announcements->links() }}
    </div>

</div>

@endsection