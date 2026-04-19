@extends('layouts.app')

@section('content')

<div class="min-h-screen  py-10">

    <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row gap-8">

        <!-- LEFT SIDE: MAP -->
        <div class="md:w-2/3">

            <div id="map" class="w-full h-[520px] rounded-2xl shadow-sm border border-gray-100"></div>

        </div>

        <!-- RIGHT SIDE: WRITING PANEL -->
        <div class="md:w-1/3">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 h-full">

                <h1 class="text-3xl font-bold text-gray-800 leading-tight">
                    Explore your surroundings
                </h1>

                <p class="text-gray-600 mt-4 leading-relaxed">
                    This map shows your home area and nearby activity.
                    Posts and emergency alerts appear based on your location.
                </p>

                <div class="mt-6 space-y-3 text-sm text-gray-500">

                    <p>🏠 Green circle = your home radius</p>
                    <p>📍 Blue marker = post location</p>
                    <p>🚨 Red marker = emergency alert</p>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQ7vDubyjIhXt__oAxWJCR_bcpj_Un2R4"></script>

<script>
const locationQuery = "{{ request('location') }}";

/* 🚨 EMERGENCY LAT/LNG FROM URL */
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
        zoom: 13
    });

    /* 🏠 HOME RADIUS */
    new google.maps.Circle({
        center: homeLocation,
        radius: data.radius,
        map: map,
        fillColor: '#10b981',
        fillOpacity: 0.15,
        strokeColor: '#10b981',
        strokeOpacity: 0.6,
        strokeWeight: 2
    });

    /* 🏠 HOME MARKER */
    new google.maps.Marker({
        position: homeLocation,
        map: map,
        title: 'Your Home'
    });

    /* 📍 POST LOCATION (TEXT BASED) */
    if (locationQuery) {

        const geocoder = new google.maps.Geocoder();

        geocoder.geocode({ address: locationQuery }, function(results, status) {

            if (status === 'OK') {

                const postLocation = results[0].geometry.location;

                map.setCenter(postLocation);

                new google.maps.Marker({
                    position: postLocation,
                    map: map,
                    title: locationQuery,
                    icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                });

            }

        });
    }

    /* 🚨 EMERGENCY MARKER */
    if (emergencyLat && emergencyLng) {

        new google.maps.Marker({
            position: { lat: emergencyLat, lng: emergencyLng },
            map: map,
            title: "Emergency Location",
            icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
        });

        map.setZoom(15);
    }

});
</script>

@endsection