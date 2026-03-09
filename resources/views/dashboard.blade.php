@extends('layouts.app')

@section('content')
<h1>Welcome, {{ Auth::user()->name }}!</h1>
<!-- 
<div>
    <a href="{{ route('lost-items.index') }}">
        <button>Lost & Found</button>
    </a>
<<<<<<< HEAD
</div> -->

=======
    <br><br>
    <a href="{{ route('announcements.index') }}">
        <button>Announcements</button>
    </a>
</div>
>>>>>>> fb4efa5e81ea071c47162cd0fc361cad66c82e02
@endsection