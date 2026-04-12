@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 30px;">Create Marketplace Listing</h1>

    @if($errors->any())
        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 10px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('marketplace.store') }}" enctype="multipart/form-data" style="background-color: #f9f9f9; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        @csrf

        <!-- Title -->
        <div style="margin-bottom: 20px;">
            <label for="title" style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">
                📝 Listing Title *
            </label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="e.g., Used bicycle for sale" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box;" required>
            <small style="color: #999;">Be descriptive and specific</small>
        </div>

        <!-- Description -->
        <div style="margin-bottom: 20px;">
            <label for="description" style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">
                📝 Description *
            </label>
            <textarea id="description" name="description" rows="5" placeholder="Describe your item in detail. Include condition, features, etc." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box; font-family: inherit;" required>{{ old('description') }}</textarea>
            <small style="color: #999;">Maximum 1000 characters</small>
        </div>

        <!-- Price -->
        <div style="margin-bottom: 20px;">
            <label for="price" style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">
                💰 Price (tk) *
            </label>
            <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" placeholder="0.00" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box;" required>
            <small style="color: #999;">Enter the price in tk (e.g., 2500.00)</small>
        </div>

        <!-- Image Upload -->
        <div style="margin-bottom: 30px;">
            <label for="image" style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">
                📷 Upload Photo (Optional)
            </label>
            <input type="file" id="image" name="image" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box;">
            <small style="color: #999;">Supported formats: JPEG, PNG, JPG, GIF, WebP (Max 5MB)</small>
            @if(old('image'))
                <div style="margin-top: 10px; color: #666;">
                    📷 File selected: {{ old('image') }}
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background-color: #007bff; color: white; padding: 12px 30px; border: none; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; flex: 1;">
                🚀 Create Listing
            </button>
            <a href="{{ route('marketplace.index') }}" style="background-color: #6c757d; color: white; padding: 12px 30px; border-radius: 5px; font-size: 16px; font-weight: bold; text-decoration: none; text-align: center; display: inline-block;">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection