@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-8 lg:grid-cols-[1.3fr_0.7fr]">
        <div class="rounded-3xl bg-white/85 backdrop-blur-xl border border-white/60 shadow-2xl p-8">
            <div class="flex flex-wrap items-center gap-3">
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-sky-100 text-sky-800 border border-sky-200">{{ ucfirst($donationPost->approval_status) }}</span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">{{ ucfirst($donationPost->status) }}</span>
                <span class="text-xs text-slate-500">{{ ucfirst($donationPost->category) }}</span>
            </div>

            <h1 class="mt-4 text-3xl font-bold text-slate-900">{{ $donationPost->title }}</h1>
            <p class="mt-2 text-sm text-slate-500">Requested by {{ $donationPost->user->name }} • {{ $donationPost->location ?: 'Location not set' }}</p>

            <p class="mt-6 text-slate-700 leading-7 whitespace-pre-line">{{ $donationPost->description }}</p>

            @if($donationPost->admin_note)
                <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                    <span class="font-semibold">Admin note:</span> {{ $donationPost->admin_note }}
                </div>
            @endif

            @if(($donationPost->proof_files ?? []) && (auth()->user()->isAdmin() || auth()->id() === $donationPost->user_id))
                <div class="mt-6">
                    <h2 class="text-lg font-semibold text-slate-900">Proof files</h2>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach(($donationPost->proof_files ?? []) as $idx => $proof)
                            <a href="{{ route('donation-posts.proof.download', [$donationPost, $idx]) }}"
                               class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                {{ $proof['name'] ?? 'Proof file ' . ($idx + 1) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            @php
                $goal = max((float) $donationPost->goal_amount, 1);
                $raised = (float) $donationPost->raised_amount;
                $pct = min(100, ($raised / $goal) * 100);
            @endphp
            <div class="rounded-3xl bg-white/85 backdrop-blur-xl border border-white/60 shadow-2xl p-6">
                <h2 class="text-xl font-bold text-slate-900">Fund Progress</h2>
                <div class="mt-4 h-3 rounded-full bg-slate-200 overflow-hidden">
                    <div class="h-full bg-emerald-500" style="width: {{ $pct }}%"></div>
                </div>
                <p class="mt-3 text-sm text-slate-700">{{ number_format($raised, 2) }} BDT raised of {{ number_format($goal, 2) }} BDT</p>

                @if($donationPost->approval_status === 'approved' && $donationPost->status === 'active')
                    <form method="POST" action="{{ route('payments.posts.donate', $donationPost) }}" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Donate Amount (BDT)</label>
                            <input type="number" name="amount" min="1" step="0.01" required
                                   class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Payment Method</label>
                            <select name="payment_method" required class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="bkash">bKash</option>
                                <option value="card">Card</option>
                                <option value="bank">Bank</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white hover:bg-emerald-700">
                            Confirm and Pay
                        </button>
                    </form>
                @else
                    <p class="mt-6 text-sm text-slate-600">This request is not currently accepting donations.</p>
                @endif
            </div>

            <div class="rounded-3xl bg-white/85 backdrop-blur-xl border border-white/60 shadow-2xl p-6">
                <h2 class="text-lg font-bold text-slate-900">Recent Donations</h2>
                <div class="mt-4 space-y-3">
                    @forelse($recentDonations as $donation)
                        <div class="rounded-xl bg-slate-50 p-3 flex justify-between text-sm">
                            <span class="text-slate-700">{{ $donation->donor_name }}</span>
                            <span class="font-semibold text-slate-900">{{ number_format((float) $donation->amount, 2) }} BDT</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No donations yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
