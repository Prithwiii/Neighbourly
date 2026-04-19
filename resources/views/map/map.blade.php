@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center px-6 py-10">

    <div class="w-full max-w-7xl grid md:grid-cols-3 gap-10">

        <!-- LEFT: MAP -->
        <div class="md:col-span-2 relative">

            <!-- soft glow behind map -->
            <div class="absolute -top-10 -left-10 w-72 h-72 bg-emerald-400/10 blur-3xl rounded-full"></div>

            <div id="map"
                 class="w-full h-[550px] rounded-3xl
                        border border-white/10
                        shadow-2xl overflow-hidden"></div>

        </div>

        <!-- RIGHT: FLOATING INFO PANEL -->
        <div class="md:col-span-1">

            <div class="sticky top-10">

                <!-- glow -->
                <div class="absolute -top-10 -right-10 w-60 h-60 bg-emerald-400/20 blur-3xl rounded-full"></div>

                <!-- CARD -->
                <div class="relative p-8 rounded-3xl
                            bg-white/10 backdrop-blur-xl
                            border border-white/10
                            shadow-2xl text-white space-y-6">

                    <h1 class="text-3xl font-semibold leading-tight">
                        Explore your surroundings
                    </h1>

                    <p class="text-white/70 leading-relaxed">
                        This map shows your home area and nearby activity.
                        Posts and alerts appear based on your location.
                    </p>

                    <div class="space-y-2 text-sm text-white/50">

                        <p> Green circle = home radius</p>
                        <p> Blue marker = post location</p>
                        <p> Red marker = emergency alert</p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQ7vDubyjIhXt__oAxWJCR_bcpj_Un2R4"></script>

<script>
const locationQuery = "{{ request('location') }}";

const urlParams = new URLSearchParams(window.location.search);
const emergencyLat = parseFloat(urlParams.get('lat'));
const emergencyLng = parseFloat(urlParams.get('lng'));

fetch('/api/home-radius', {
    headers: { 'Accept': 'application/json' }
})
.then(res => res.json())
.then(data => {

    const homeLocation = {
        lat: parseFloat(data.lat),
        lng: parseFloat(data.lng)
    };

    const map = new google.maps.Map(document.getElementById('map'), {
        center: (emergencyLat && emergencyLng)
            ? { lat: emergencyLat, lng: emergencyLng }
            : homeLocation,
        zoom: 13,
        disableDefaultUI: true
    });

    // HOME RADIUS
    new google.maps.Circle({
        center: homeLocation,
        radius: data.radius,
        map: map,
        fillColor: '#10b981',
        fillOpacity: 0.12,
        strokeColor: '#10b981',
        strokeOpacity: 0.6,
        strokeWeight: 2
    });

    // HOME MARKER
    new google.maps.Marker({
        position: homeLocation,
        map: map,
        title: 'Home'
    });

    // POST LOCATION
    if (locationQuery) {

        const geocoder = new google.maps.Geocoder();

        geocoder.geocode({ address: locationQuery }, function(results, status) {

            if (status === 'OK') {

                const postLocation = results[0].geometry.location;

                map.setCenter(postLocation);

                new google.maps.Marker({
                    position: postLocation,
                    map: map,
                    icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                });

            }

        });
    }

    // EMERGENCY
    if (emergencyLat && emergencyLng) {

        new google.maps.Marker({
            position: { lat: emergencyLat, lng: emergencyLng },
            map: map,
            icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
        });

        map.setZoom(15);
    }

});
</script>

@endsection