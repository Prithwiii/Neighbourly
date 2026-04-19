@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-emerald-950">Donation Requests Pending Approval</h1>
    <p class="mt-2 text-slate-600">Review, approve, or reject community help requests.</p>

    @if(session('success'))
        <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">{{ session('success') }}</div>
    @endif

    @if(session('info'))
        <div class="mt-6 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sky-800 text-sm">{{ session('info') }}</div>
    @endif

    <div class="mt-8 space-y-5">
        @forelse($pendingPosts as $post)
            <div class="rounded-3xl bg-white/85 backdrop-blur-xl border border-white/60 shadow-xl p-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ $post->title }}</h2>
                        <p class="mt-1 text-sm text-slate-600">By {{ $post->user->name }} • {{ ucfirst($post->category) }} • {{ number_format((float) $post->goal_amount, 2) }} BDT</p>
                        <p class="mt-3 text-sm text-slate-700 line-clamp-3">{{ $post->description }}</p>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <form method="POST" action="{{ route('admin.donation-posts.approve', $post) }}" class="space-y-3">
                        @csrf
                        <textarea name="admin_note" rows="3" placeholder="Optional approval note"
                                  class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                        <button type="submit" class="w-full rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white hover:bg-emerald-700">Approve</button>
                    </form>

                    <form method="POST" action="{{ route('admin.donation-posts.reject', $post) }}" class="space-y-3">
                        @csrf
                        <textarea name="admin_note" rows="3" placeholder="Required rejection note" required
                                  class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-red-500 focus:ring-red-500"></textarea>
                        <button type="submit" class="w-full rounded-2xl bg-red-600 px-5 py-3 font-semibold text-white hover:bg-red-700">Reject</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-3xl bg-white/80 border border-white/60 p-8 text-center text-slate-600">No pending requests right now.</div>
        @endforelse
    </div>

    <div class="mt-8">{{ $pendingPosts->links() }}</div>
</div>
@endsection
