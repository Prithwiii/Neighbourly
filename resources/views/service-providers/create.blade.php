@extends('layouts.app')

@section('content')
<div style="max-width: 760px; margin: 0 auto;">
    <h1>Join as a Service Provider</h1>
    <p>Submit your details. Your profile will be visible only after admin verification.</p>

    @if($errors->any())
        <div style="background:#fde2e2; color:#8a1f1f; padding:12px; border-radius:6px; margin-bottom:16px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('providers.store') }}" enctype="multipart/form-data" style="display:grid; gap:14px;">
        @csrf

        <div>
            <label>Full Name</label><br>
            <input type="text" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" required style="width:100%; padding:8px;">
        </div>

        <div>
            <label>Phone Number</label><br>
            <div style="display:flex; align-items:center; gap:0; width:100%;">
                <span style="padding:8px 12px; background:#f3f4f6; border:1px solid #d1d5db; border-right:none; border-radius:6px 0 0 6px; color:#374151; white-space:nowrap;">+880</span>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="1XXXXXXXXX" required style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:0 6px 6px 0;">
            </div>
        </div>

        <div>
            <label>Service Category</label><br>
            <select name="service_category" required style="width:100%; padding:8px;">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" @selected(old('service_category') === $category)>{{ $category }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Location</label><br>
            <input type="text" name="location" value="{{ old('location') }}" placeholder="Area, street or landmark" required style="width:100%; padding:8px;">
        </div>

        <div>
            <label>Experience (years)</label><br>
            <input type="number" name="experience_years" min="0" max="80" value="{{ old('experience_years', 0) }}" required style="width:100%; padding:8px;">
        </div>

        <div>
            <label>Description</label><br>
            <textarea name="description" rows="4" required style="width:100%; padding:8px;">{{ old('description') }}</textarea>
        </div>

        <div>
            <label>Availability Status</label><br>
            <select name="availability_status" required style="width:100%; padding:8px;">
                <option value="available" @selected(old('availability_status') === 'available')>Available</option>
                <option value="busy" @selected(old('availability_status') === 'busy')>Busy</option>
                <option value="offline" @selected(old('availability_status') === 'offline')>Offline</option>
            </select>
        </div>

        <div>
            <label>Availability Details</label><br>
            <input type="text" name="availability_details" value="{{ old('availability_details') }}" placeholder="24/7 or 9:00 AM - 6:00 PM" style="width:100%; padding:8px;">
        </div>

        <div>
            <label>ID/Document Upload (Optional)</label><br>
            <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <button type="submit" style="padding:10px 14px;">Submit Provider Application</button>
    </form>
</div>
@endsection
