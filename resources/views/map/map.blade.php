@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- Title -->
    <h1 class="text-2xl font-bold mb-6">Map Around Your Home</h1>

    <!-- Map Container -->
    <div id="map" class="w-full h-[500px] rounded-xl shadow"></div>

</div>

<!-- Pass location from URL -->
<script>
    const locationQuery = "{{ request('location') }}";
</script>

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

        // 🔴 HOME RADIUS
        new google.maps.Circle({
            center: homeLocation,
            radius: data.radius,
            map: map,
            fillColor: '#ef4444',
            fillOpacity: 0.2,
            strokeColor: '#ef4444',
            strokeOpacity: 0.6,
            strokeWeight: 2
        });

        // 🏠 HOME MARKER
        new google.maps.Marker({
            position: homeLocation,
            map: map,
            title: 'Your Home'
        });

        // 🔵 POST LOCATION (if exists)
        if (locationQuery) {

            const geocoder = new google.maps.Geocoder();

            geocoder.geocode({ address: locationQuery }, function(results, status) {

                if (status === 'OK') {

                    const postLocation = results[0].geometry.location;

                    // Move map to post
                    map.setCenter(postLocation);

                    // Add marker for post
                    new google.maps.Marker({
                        position: postLocation,
                        map: map,
                        title: locationQuery,
                        icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                    });

                } else {
                    console.log("Geocode failed: " + status);
                }

            });
        }

    });
</script>

@endsection