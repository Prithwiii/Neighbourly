@extends('layouts.app')

@section('content')

<!-- SCROLLABLE WRAPPER -->

{{-- <div class="h-[calc(100vh-7rem)] overflow-y-auto px-6"> --}}

```
<div class="max-w-3xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6 p-6 rounded-2xl
                bg-white/20 backdrop-blur-xl
                border border-white/30 shadow-xl text-center">

        <h1 class="text-2xl font-semibold text-emerald-700">
            Announcements
        </h1>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl
                    bg-green-100 text-green-700
                    border border-green-300 shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- ANNOUNCEMENTS -->
    @forelse($announcements as $announcement)

        <div class="mb-6 p-6 rounded-2xl
                    bg-white/20 backdrop-blur-xl
                    border border-white/30 shadow-xl
                    hover:shadow-2xl transition">

            <!-- TITLE -->
            <h2 class="text-xl font-semibold mb-2">
                {{ $announcement->headline }}
            </h2>

            <!-- CONTENT -->
            <p class="text-gray-700 mb-4">
                {{ $announcement->content }}
            </p>

            <!-- META -->
            <div class="text-xs text-gray-600">
                Posted on {{ $announcement->created_at->format('M d, Y') }}
            </div>

        </div>

    @empty

        <div class="text-center text-gray-700">
            No announcements yet.
        </div>

    @endforelse

    <!-- PAGINATION -->
    <div class="mt-6">
        {{ $announcements->links() }}
    </div>

</div>
```

</div>

@endsection
