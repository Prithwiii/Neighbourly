@extends('layouts.app')

@section('content')
    <h1>Report Lost Item</h1>

    <form method="POST" action="/lost-items" enctype="multipart/form-data">
        @csrf

        <input type="text" name="username" placeholder="username" required>
        <input type="text" name="phone" placeholder="phone" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="text" name="location" placeholder="Location" required>
        <input type="date" name="date_lost" required>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Submit</button>
    </form>
@endsection