@extends('layouts.app')

@section('content')
    <h1>Admins can create announcements here</h1>

    <form method="POST" action="{{ route('announcements.store') }}" enctype="multipart/form-data">
        @csrf
        <div>
            <div><label for="headline">Headline:</label></div>
            <div><textarea name="headline" placeholder="Headline" required></textarea></div>
        </div>
        <div>
            <div><label for="content">Content:</label></div>
            <div><textarea name="content" placeholder="Content" required></textarea></div>
        </div>
        <button type="submit">Submit</button>
    </form>
@endsection