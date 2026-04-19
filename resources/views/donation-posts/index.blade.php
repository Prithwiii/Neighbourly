@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="mb-8 rounded-3xl bg-white/85 backdrop-blur-xl border border-white/60 shadow-2xl p-6 md:p-8">
        <div class="flex flex-col gap-5 md:flex-row md:items-start md:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs uppercase tracking-[0.3em] text-emerald-700 font-semibold">Donation Requests</p>
                <h1 class="mt-3 text-3xl md:text-4xl font-bold text-emerald-950">Community Donation Requests</h1>
                <p class="mt-3 text-slate-600 leading-7">Support verified requests and track transparent progress.</p>
            </div>

            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.donation-posts.index') }}" class="shrink-0 inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white shadow-lg shadow-emerald-200 transition hover:-translate-y-0.5 hover:bg-emerald-700 no-underline">
                    <span>Review Help Request</span>
                    @if(!empty($pendingDonationCount) && $pendingDonationCount > 0)
                        <span class="ml-3 inline-flex min-w-7 h-7 items-center justify-center rounded-full bg-red-600 px-2 text-xs font-bold text-white">
                            {{ $pendingDonationCount }}
                        </span>
                    @endif
                </a>
            @else
                <a href="{{ route('donation-posts.create') }}" class="shrink-0 inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white shadow-lg shadow-emerald-200 transition hover:-translate-y-0.5 hover:bg-emerald-700 no-underline">
                    Request Help
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($posts as $post)
            @php
                $goal = max((float) $post->goal_amount, 1);
                $raised = (float) $post->raised_amount;
                $pct = min(100, ($raised / $goal) * 100);
            @endphp
            <div class="rounded-3xl bg-white/85 backdrop-blur-xl border border-white/60 shadow-xl p-6">
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-semibold">{{ ucfirst($post->category) }}</p>
                <h2 class="mt-2 text-xl font-bold text-slate-900">{{ $post->title }}</h2>
                <p class="mt-2 text-sm text-slate-600 line-clamp-3">{{ $post->description }}</p>

                <div class="mt-4 h-2 rounded-full bg-slate-200 overflow-hidden">
                    <div class="h-full bg-emerald-500" style="width: {{ $pct }}%"></div>
                </div>

                <div class="mt-3 text-sm text-slate-700">
                    <span class="font-semibold">Raised:</span> {{ number_format($raised, 2) }} BDT
                    <span class="mx-1">/</span>
                    <span class="font-semibold">Goal:</span> {{ number_format($goal, 2) }} BDT
                </div>

                <div class="mt-5 flex items-center justify-between">
                    <span class="text-xs text-slate-500">{{ $post->location ?: 'Location not set' }}</span>
                    <a href="{{ route('donation-posts.show', $post) }}" class="rounded-xl bg-slate-900 text-white px-4 py-2 text-sm font-semibold hover:bg-slate-800">
                        Donate
                    </a>
                </div>
            </div>
        @empty
            <div class="md:col-span-2 xl:col-span-3 rounded-3xl bg-white/80 border border-white/60 p-8 text-center text-slate-600">
                No approved donation requests yet.
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $posts->links() }}</div>
</div>
@endsection
