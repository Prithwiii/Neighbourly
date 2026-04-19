@extends('layouts.app')

@section('content')
@php
    $statusClass = match ($transaction->status) {
        'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        'failed' => 'bg-red-100 text-red-800 border-red-200',
        'canceled' => 'bg-amber-100 text-amber-800 border-amber-200',
        default => 'bg-sky-100 text-sky-800 border-sky-200',
    };
@endphp

<div class="max-w-3xl mx-auto px-4 py-10">
    <div class="rounded-3xl bg-white/85 backdrop-blur-xl border border-white/60 shadow-2xl p-8">
        @if(session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Donation Receipt</h1>
                <p class="mt-2 text-slate-600">Order: {{ $transaction->order_id }}</p>
            </div>

            <span class="inline-flex items-center rounded-full border px-4 py-2 text-sm font-semibold {{ $statusClass }}">
                {{ ucfirst($transaction->status) }}
            </span>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Donation Post</p>
                <p class="mt-1 text-lg font-semibold text-slate-900">{{ $transaction->donationPost->title }}</p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Amount</p>
                <p class="mt-1 text-lg font-semibold text-slate-900">{{ number_format((float) $transaction->amount, 2) }} {{ $transaction->currency }}</p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Payment Method</p>
                <p class="mt-1 text-lg font-semibold text-slate-900">{{ strtoupper($transaction->payment_method) }}</p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Gateway Transaction</p>
                <p class="mt-1 text-lg font-semibold text-slate-900">{{ $transaction->transaction_id ?? 'Pending' }}</p>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('donation-posts.show', $transaction->donationPost) }}" class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">
                Back to Request
            </a>
            <a href="{{ route('donation-posts.index') }}" class="rounded-2xl border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 hover:bg-slate-50">
                View All Requests
            </a>
        </div>
    </div>
</div>
@endsection
