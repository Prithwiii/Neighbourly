@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr]">
        <div class="rounded-3xl bg-white/80 backdrop-blur-xl border border-white/60 shadow-2xl p-8">
            <div class="mb-8">
                <p class="text-sm uppercase tracking-[0.3em] text-emerald-700 font-semibold">Community donations</p>
                <h1 class="mt-3 text-4xl font-bold text-emerald-950">Secure SSLCommerz checkout</h1>
                <p class="mt-3 text-slate-600 leading-7">
                    Support the Neighbourly community with a payment flow that is isolated, auditable, and ready for sandbox or live SSLCommerz credentials.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="space-y-1 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('payments.donations.store') }}" class="space-y-5">
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2" for="donor_name">Full name</label>
                        <input id="donor_name" name="donor_name" type="text" value="{{ old('donor_name', auth()->user()->name ?? '') }}"
                               class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2" for="donor_phone">Phone number</label>
                        <input id="donor_phone" name="donor_phone" type="text" value="{{ old('donor_phone', auth()->user()->phone ?? '') }}"
                               class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2" for="donor_email">Email address</label>
                        <input id="donor_email" name="donor_email" type="email" value="{{ old('donor_email', auth()->user()->email ?? '') }}"
                               class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2" for="amount">Amount ({{ config('services.sslcommerz.currency', 'BDT') }})</label>
                        <input id="amount" name="amount" type="number" min="1" step="0.01" value="{{ old('amount') }}"
                               class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="purpose">Purpose</label>
                    <input id="purpose" name="purpose" type="text" value="{{ old('purpose') }}"
                           placeholder="Example: Community support"
                           class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="message">Message</label>
                    <textarea id="message" name="message" rows="5"
                              class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                              placeholder="Optional note for the community">{{ old('message') }}</textarea>
                </div>

                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700">
                        Continue to SSLCommerz
                    </button>

                    <p class="text-sm text-slate-500">
                        You will be redirected to SSLCommerz for the secure payment step.
                    </p>
                </div>
            </form>
        </div>

        <div class="rounded-3xl bg-slate-950 text-white shadow-2xl p-8 border border-slate-800">
            <p class="text-sm uppercase tracking-[0.3em] text-emerald-300 font-semibold">Professional module</p>
            <h2 class="mt-3 text-3xl font-bold">Why this implementation is clean</h2>

            <ul class="mt-6 space-y-4 text-sm leading-7 text-slate-300">
                <li class="rounded-2xl border border-white/10 bg-white/5 p-4">Dedicated controller, service, and request class for payment logic.</li>
                <li class="rounded-2xl border border-white/10 bg-white/5 p-4">Local transaction record keeps every payment auditable.</li>
                <li class="rounded-2xl border border-white/10 bg-white/5 p-4">Sandbox/live mode is controlled entirely by environment variables.</li>
                <li class="rounded-2xl border border-white/10 bg-white/5 p-4">Success, fail, cancel, and IPN callbacks are separated and named clearly.</li>
            </ul>
        </div>
    </div>
</div>
@endsection