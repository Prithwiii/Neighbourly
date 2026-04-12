@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- Title -->
    <h1 class="text-2xl font-bold mb-6">Map Around Your Home</h1>

    <!-- Map Container -->
    <div id="map" class="w-full h-[500px] rounded-xl shadow"></div>

</div>

<!-- Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQ7vDubyjIhXt__oAxWJCR_bcpj_Un2R4"></script>

<script>
    fetch('/api/home-radius', {
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {

        const homeLocation = {
            lat: parseFloat(data.lat),
            lng: parseFloat(data.lng)
        };

        const map = new google.maps.Map(document.getElementById('map'), {
            center: homeLocation,
            zoom: 13
        });

        // Circle (radius)
        new google.maps.Circle({
            center: homeLocation,
            radius: data.radius,
            map: map,
            fillColor: '#ef4444',   // softer red
            fillOpacity: 0.2,
            strokeColor: '#ef4444',
            strokeOpacity: 0.6,
            strokeWeight: 2
        });

        // Marker
        new google.maps.Marker({
            position: homeLocation,
            map: map,
            title: 'Your Home'
        });

    });
</script>

@endsection