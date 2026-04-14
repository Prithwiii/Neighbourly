@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Create Announcement</h2>

<form method="POST" action="/announcements">
    @csrf

    <input type="text" name="headline" placeholder="Headline"
        class="w-full p-2 border rounded mb-3">

    <textarea name="content" placeholder="Content"
        class="w-full p-2 border rounded mb-3"></textarea>

    <button class="bg-green-600 text-white px-4 py-2 rounded">
        Submit
    </button>
</form>
@endsection