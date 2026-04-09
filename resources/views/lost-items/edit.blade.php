<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Lost Item - Neighbourly</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Edit Lost Item</h1>
    
    <form method="POST" action="{{ route('lost-items.update', $lostItem->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div>
            <label>Username:</label>
            <input type="text" name="username" value="{{ $lostItem->username }}" required>
        </div>
        
        <div>
            <label>Phone:</label>
            <input type="text" name="phone" value="{{ $lostItem->phone }}" required>
        </div>
        
        <div>
            <label>Description:</label>
            <textarea name="description" required>{{ $lostItem->description }}</textarea>
        </div>
        
        <div>
            <label>Location:</label>
            <input type="text" name="location" value="{{ $lostItem->location }}" required>
        </div>
        
        <div>
            <label>Date Lost:</label>
            <input type="date" name="date_lost" value="{{ $lostItem->date_lost }}" required>
        </div>
        
        <div>
            <label>Image:</label>
            <input type="file" name="image" accept="image/*">
            @if($lostItem->image)
                <p>Current image: {{ $lostItem->image }}</p>
            @endif
        </div>
        
        <button type="submit">Update Item</button>
        <a href="{{ route('lost-items.index') }}" class="btn">Cancel</a>
    </form>
</div>
</body>
</html>
