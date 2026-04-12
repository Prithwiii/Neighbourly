@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Create Issue</h2>

<form method="POST" action="/issues">
    @csrf

    <input type="text" name="title" placeholder="Title"
        class="w-full p-2 border rounded mb-3">

    <textarea name="description" placeholder="Description"
        class="w-full p-2 border rounded mb-3"></textarea>

    <button class="bg-green-600 text-white px-4 py-2 rounded">
        Submit
    </button>
</form>
@endsection