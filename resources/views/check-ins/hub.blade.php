@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 md:px-6 pb-10">

    <section class="rounded-3xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-2xl p-6 md:p-8 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-white/80">Community Help</p>
                <h1 class="text-3xl md:text-4xl font-semibold text-white mt-2">Check-in Requests</h1>
                <p class="text-white/90 mt-2 max-w-2xl">
                    Browse requests for check-ins and help your neighbours. Ordered by urgency, then location proximity, then newest.
                </p>
            </div>
            <a href="{{ route('check-ins.create') }}"
               class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition shadow-lg">
                Post a Check-in Need
            </a>
        </div>
    </section>

    @if($requests->isEmpty())
        <div class="rounded-3xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-lg p-8 text-center">
            <p class="text-white/90">No open check-in requests at the moment.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($requests as $request)
                <div class="rounded-2xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-lg hover:shadow-xl transition p-5 md:p-6">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-semibold">
                                        {{ substr($request->requester->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-white truncate">{{ $request->title }}</h3>
                                    <p class="text-sm text-white/80">by {{ $request->requester->name }}</p>
                                </div>
                            </div>

                            <p class="text-white/90 mb-3">{{ Str::limit($request->description, 150) }}</p>

                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                                    {{ $request->urgency_level === 'high' ? 'bg-red-600/80 text-white' : ($request->urgency_level === 'medium' ? 'bg-yellow-600/80 text-white' : 'bg-blue-600/80 text-white') }}">
                                    {{ ucfirst($request->urgency_level) }} Urgency
                                </span>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-white/20 text-white">
                                    {{ str_replace('_', ' ', ucfirst($request->work_type)) }}
                                </span>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-white/20 text-white">
                                    {{ $request->location_name ?? 'Location not specified' }}
                                </span>
                            </div>

                            <p class="text-xs text-white/70">
                                Posted {{ $request->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-2 md:flex-col-reverse">
                            <a href="{{ route('check-ins.show', $request) }}"
                               class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium transition">
                                View Details
                            </a>
                            @if($request->activeAssignment)
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-blue-600/80 text-white text-xs font-medium">
                                    Assigned
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
