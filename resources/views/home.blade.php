@extends('layouts.app')



@section('content')

<div class="min-h-screen flex items-center justify-center px-6 relative overflow-x-hidden">

    <!-- 🟢 TOP LEFT BRAND BLOCK -->
    <div class="hidden lg:block absolute top-10 left-10 text-white max-w-xs">

        <h1 class="text-6xl font-bold leading-tight">
            Neighbourly
        </h1>

        <p class="text-white/100 mt-3 text-l leading-relaxed">
            A community platform where people connect, help each other,
            and stay informed about what’s happening around them.
        </p>

    </div>



    <!-- 🟢 RIGHT BIG WRITING -->
    <div class="hidden lg:block absolute right-10 top-1/2 -translate-y-1/2 text-white max-w-xs text-right">

        <h2 class="text-6xl font-bold leading-snug">
            Stay connected<br>with your community
        </h2>

        <p class="text-white/100 mt-4 text-l leading-relaxed">
            Everything you need is in one place.
            From finding lost items to helping others,
            Neighbourly brings real-world connections into one simple platform.
        </p>

    </div>

    <!-- 🧠 CENTER HUB (YOUR CODE UNCHANGED) -->
    
    <div class="w-[1000px] py-6 px-10 rounded-3xl
            bg-white/20 backdrop-blur-xl
            border border-white/30
            shadow-2xl translate-x-6">

        <!-- GRID MENU -->
        <div class="grid grid-cols-2 gap-5">

            <a href="{{ route('lost-items.hub') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Lost & Found
            </a>

            <a href="{{ route('announcements.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Announcements
            </a>

            <a href="{{ route('issues.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Issues
            </a>

            <a href="{{ route('marketplace.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Marketplace
            </a>

            <a href="{{ route('messages.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Messages
            </a>

            <a href="{{ route('map') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Map
            </a>

            <a href="{{ route('services.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Local Services
            </a>

<<<<<<< HEAD
                <a href="{{ route('emergency-alerts.index') }}"
                    class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                             backdrop-blur-md border border-white/30
                             shadow-md hover:shadow-xl transition text-center text-emerald-900">
                     Emergency Alerts
                </a>
            
=======
            <a href="{{ route('posts.hub') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Community Blog
            </a>

>>>>>>> prithwi-all
            <a href="{{ route('news.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                News
            </a>

            <a href="{{ route('donation-posts.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Donations
            </a>

            <a href="{{ route('enlistings.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Enlist for Work
            </a>

            <a href="{{ route('jobs.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Jobs
            </a>

        </div>

        <!-- EMERGENCY -->
        <div class="mt-6 text-center">

            <button onclick="sendEmergency()"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl shadow-lg">
                🚨 Emergency Help
            </button>

        </div>

    </div>

</div>

<!-- SCRIPTS (UNCHANGED) -->
<script>
function sendEmergency() {

    navigator.geolocation.getCurrentPosition(function(position) {

        fetch('/emergency', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                lat: position.coords.latitude,
                lng: position.coords.longitude
            })
        })
        .then(res => res.json())
        .then(data => {
            alert("Emergency sent to nearby users!");
        });

    });

}
</script>

<script>
function updateLocation() {

    if (!navigator.geolocation) return;

    navigator.geolocation.getCurrentPosition(function(position) {

        fetch('/update-location', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                lat: position.coords.latitude,
                lng: position.coords.longitude
            })
        });

    });

}

updateLocation();
</script>

@endsection