@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1 style="margin-bottom: 30px;">Report a Community Issue</h1>

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

    <form method="POST" action="{{ route('issues.store') }}" enctype="multipart/form-data" style="background-color: #f9f9f9; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        @csrf

        <!-- Title -->
        <div style="margin-bottom: 20px;">
            <label for="title" style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">
                📝 Issue Title *
            </label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="e.g., Large pothole on main street" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box;" required>
            <small style="color: #999;">Be specific about what the issue is</small>
        </div>

        <!-- Description -->
        <div style="margin-bottom: 20px;">
            <label for="description" style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">
                📝 Detailed Description *
            </label>
            <textarea id="description" name="description" rows="5" placeholder="Describe the issue in detail. Include when you noticed it, any impacts, etc." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box; font-family: inherit;" required>{{ old('description') }}</textarea>
            <small style="color: #999;">Maximum 1000 characters</small>
        </div>

        <!-- Category -->
        <div style="margin-bottom: 20px;">
            <label for="category" style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">
                🏷 Category *
            </label>
            <select id="category" name="category" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box;" required>
                <option value="">-- Select a category --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Location -->
        <div style="margin-bottom: 20px;">
            <label for="location" style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">
                📍 Location *
            </label>
            <input type="text" id="location" name="location" value="{{ old('location') }}" placeholder="e.g., Corner of Oak Street and Main Avenue" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box;" required>
            <small style="color: #999;">Be as specific as possible</small>
        </div>

        <!-- Severity -->
        <div style="margin-bottom: 20px;">
            <label for="severity" style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">
                ⚠ Severity Level
            </label>
            <select id="severity" name="severity" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box;">
                <option value="">-- Not specified --</option>
                <option value="low" {{ old('severity') === 'low' ? 'selected' : '' }}>Low - Minor inconvenience</option>
                <option value="medium" {{ old('severity') === 'medium' ? 'selected' : '' }}>Medium - Important to address</option>
                <option value="high" {{ old('severity') === 'high' ? 'selected' : '' }}>High - Urgent concern</option>
                <option value="critical" {{ old('severity') === 'critical' ? 'selected' : '' }}>Critical - Safety hazard</option>
            </select>
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
                🚀 Submit Issue Report
            </button>
            <a href="{{ route('issues.index') }}" style="background-color: #6c757d; color: white; padding: 12px 30px; border-radius: 5px; font-size: 16px; font-weight: bold; text-decoration: none; text-align: center; display: inline-block;">
                Cancel
            </a>
        </div>

        <p style="text-align: center; color: #999; margin-top: 20px; font-size: 14px;">
            ✓ Your report will be visible to the community<br>
            ✓ Please provide accurate information<br>
            ✓ False reports may be removed
        </p>
    </form>
</div>
@endsection
