@extends('layouts.app')

@section('content')
<h1>Map Around Your Home</h1>
<div id="map" style="height: 500px; width: 100%;"></div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQ7vDubyjIhXt__oAxWJCR_bcpj_Un2R4"></script>
<script>
    fetch('/api/home-radius', {
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer {{ auth()->user()->api_token ?? "" }}' // if using token
        }
    })
    .then(res => res.json())
    .then(data => {
        const homeLocation = { lat: parseFloat(data.lat), lng: parseFloat(data.lng) };

        const map = new google.maps.Map(document.getElementById('map'), {
            center: homeLocation,
            zoom: 13
        });

        // 5 km circle
        new google.maps.Circle({
            center: homeLocation,
            radius: data.radius,
            map: map,
            fillColor: '#FF0000',
            fillOpacity: 0.2,
            strokeColor: '#FF0000',
            strokeOpacity: 0.5,
            strokeWeight: 2
        });

        // Marker at home
        new google.maps.Marker({
            position: homeLocation,
            map: map,
            title: 'Your Home'
        });

        
    });
</script>
@endsection