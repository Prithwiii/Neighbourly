<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lost Item Details - Neighbourly</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Lost Item Details</h1>
    
    <div class="lost-item-details">
        <p><strong>Name:</strong> {{ $lostItem->username }}</p>
        <p><strong>Phone:</strong> {{ $lostItem->phone }}</p>
        <p><strong>Description:</strong> {{ $lostItem->description }}</p>
        <p><strong>Location:</strong> {{ $lostItem->location }}</p>
        <p><strong>Date Lost:</strong> {{ $lostItem->date_lost }}</p>
        
        @if($lostItem->image)
            <div class="lost-item-image">
                <img src="{{ asset('storage/lost_items/' . $lostItem->image) }}" alt="Lost Item Image" style="max-width: 300px;">
            </div>
        @endif
    </div>
    
    <div class="actions">
        <a href="{{ route('lost-items.index') }}" class="btn">Back to List</a>
    </div>
</div>
</body>
</html>
