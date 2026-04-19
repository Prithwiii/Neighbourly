@extends('layouts.app')

@section('content')
@php
    $status = $payment->status;
    $statusLabel = match ($status) {
        'paid' => 'Paid',
        'failed' => 'Failed',
        'canceled' => 'Canceled',
        default => 'Pending',
    };

    $statusClass = match ($status) {
        'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        'failed' => 'bg-red-100 text-red-800 border-red-200',
        'canceled' => 'bg-amber-100 text-amber-800 border-amber-200',
        default => 'bg-sky-100 text-sky-800 border-sky-200',
    };
@endphp

<div class="max-w-3xl mx-auto px-4 py-10">
    <div class="rounded-3xl bg-white/85 backdrop-blur-xl border border-white/60 shadow-2xl p-8">
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-emerald-700 font-semibold">Donation status</p>
                <h1 class="mt-3 text-4xl font-bold text-emerald-950">{{ $payment->donor_name }}</h1>
                <p class="mt-3 text-slate-600">Order reference: {{ $payment->order_id }}</p>
            </div>

            <span class="inline-flex items-center rounded-full border px-4 py-2 text-sm font-semibold {{ $statusClass }}">
                {{ $statusLabel }}
            </span>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Amount</p>
                <p class="mt-1 text-xl font-semibold text-slate-900">{{ number_format((float) $payment->amount, 2) }} {{ $payment->currency }}</p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Gateway</p>
                <p class="mt-1 text-xl font-semibold text-slate-900">{{ strtoupper($payment->gateway_name) }}</p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Transaction ID</p>
                <p class="mt-1 text-lg font-semibold text-slate-900">{{ $payment->transaction_id ?? 'Pending' }}</p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Donor phone</p>
                <p class="mt-1 text-lg font-semibold text-slate-900">{{ $payment->donor_phone }}</p>
            </div>
        </div>

        @if ($payment->purpose || $payment->message)
            <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-5">
                <h2 class="text-lg font-semibold text-slate-900">Donation details</h2>
                @if ($payment->purpose)
                    <p class="mt-3 text-sm text-slate-600"><span class="font-semibold text-slate-800">Purpose:</span> {{ $payment->purpose }}</p>
                @endif
                @if ($payment->message)
                    <p class="mt-2 text-sm text-slate-600"><span class="font-semibold text-slate-800">Message:</span> {{ $payment->message }}</p>
                @endif
            </div>
        @endif

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('payments.donations.create') }}"
               class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700">
                Make another donation
            </a>

            <a href="{{ route('home') }}"
               class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 transition hover:bg-slate-50">
                Back to home
            </a>
        </div>
    </div>
</div>
@endsection