@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 md:px-6 pb-10">

    <section class="rounded-3xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-2xl p-5 md:p-8 space-y-7">
        <div class="grid md:grid-cols-2 gap-6 items-start">
            <div class="space-y-4">
                <p class="text-xs uppercase tracking-[0.2em] text-white/75">Weather</p>
                <h1 class="text-3xl md:text-4xl font-semibold text-white leading-tight">
                    Local Weather
                </h1>
                <p class="text-white/90 text-base md:text-lg max-w-xl">
                    7-day forecast for your saved area location.
                </p>
                <p class="inline-flex items-center rounded-full bg-white/20 border border-white/30 px-4 py-2 text-sm text-white shadow-sm">
                    Place: {{ $placeName ?? 'Unknown area' }}
                </p>
            </div>

            <div class="p-5 rounded-2xl bg-white/15 border border-white/30 shadow-xl space-y-4">
                @if($error)
                    <p class="text-sm text-red-700 bg-red-50/85 border border-red-200 rounded-xl p-3">
                        {{ $error }}
                    </p>
                @else
                    <div>
                        <h2 class="text-lg font-semibold text-white">Current Conditions</h2>
                        <p class="text-sm text-white/80 mt-1">Showing weather for {{ $placeName ?? 'your area' }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-sm text-slate-700">
                        <div class="rounded-xl bg-white/75 p-3 border border-white/50 shadow-sm">
                            <p class="text-slate-500 text-xs uppercase tracking-wide">Temperature</p>
                            <p class="text-xl font-semibold text-slate-800 mt-1">{{ $forecast['current']['temperature_2m'] ?? '-' }}{{ $forecast['current_units']['temperature_2m'] ?? '' }}</p>
                        </div>
                        <div class="rounded-xl bg-white/75 p-3 border border-white/50 shadow-sm">
                            <p class="text-slate-500 text-xs uppercase tracking-wide">Feels Like</p>
                            <p class="text-xl font-semibold text-slate-800 mt-1">{{ $forecast['current']['apparent_temperature'] ?? '-' }}{{ $forecast['current_units']['apparent_temperature'] ?? '' }}</p>
                        </div>
                        <div class="rounded-xl bg-white/75 p-3 border border-white/50 shadow-sm">
                            <p class="text-slate-500 text-xs uppercase tracking-wide">Humidity</p>
                            <p class="text-xl font-semibold text-slate-800 mt-1">{{ $forecast['current']['relative_humidity_2m'] ?? '-' }}{{ $forecast['current_units']['relative_humidity_2m'] ?? '' }}</p>
                        </div>
                        <div class="rounded-xl bg-white/75 p-3 border border-white/50 shadow-sm">
                            <p class="text-slate-500 text-xs uppercase tracking-wide">Wind Speed</p>
                            <p class="text-xl font-semibold text-slate-800 mt-1">{{ $forecast['current']['wind_speed_10m'] ?? '-' }}{{ $forecast['current_units']['wind_speed_10m'] ?? '' }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if(!$error)
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($forecastRows as $row)
                    <div class="rounded-2xl bg-white/15 backdrop-blur-xl border border-white/30 shadow-lg p-5">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-white/85">{{ \Carbon\Carbon::parse($row['date'])->format('D, M d') }}</p>
                            <span class="text-[11px] px-2 py-1 rounded-full bg-white/20 border border-white/30 text-white/90">Daily</span>
                        </div>
                        <h3 class="text-lg font-semibold text-white mt-3">{{ $row['summary'] }}</h3>
                        <div class="mt-3 pt-3 border-t border-white/20 space-y-2 text-sm text-white/90">
                            <div class="flex items-center justify-between">
                                <span>High</span>
                                <span class="font-semibold">{{ $row['max'] ?? '-' }}{{ $forecast['daily_units']['temperature_2m_max'] ?? '' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Low</span>
                                <span class="font-semibold">{{ $row['min'] ?? '-' }}{{ $forecast['daily_units']['temperature_2m_min'] ?? '' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Rain Chance</span>
                                <span class="font-semibold">{{ $row['rain_chance'] ?? '-' }}{{ $forecast['daily_units']['precipitation_probability_max'] ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

</div>
@endsection
