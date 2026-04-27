@extends('layouts.app')

@section('content')
<div style="max-width: 760px; margin: 0 auto;">
    <h1>Create Emergency Alert</h1>
    <p>Your post will be reviewed by admin before it becomes public.</p>

    @if($errors->any())
        <div style="background:#fde2e2; color:#8a1f1f; padding:12px; border-radius:6px; margin-bottom:16px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('emergency-alerts.store') }}" enctype="multipart/form-data" style="display:grid; gap:14px;">
        @csrf

        <div>
            <label>Alert Type</label><br>
            <select name="alert_type" required style="width:100%; padding:8px;">
                <option value="">Select Type</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" @selected(old('alert_type') === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Title</label><br>
            <input type="text" name="title" value="{{ old('title') }}" required style="width:100%; padding:8px;">
        </div>

        <div>
            <label>Description</label><br>
            <textarea name="description" rows="6" required style="width:100%; padding:8px;">{{ old('description') }}</textarea>
        </div>

        <div>
            <label>Photos or Videos (Optional)</label><br>
            <input type="file" name="media[]" accept="image/*,video/*" multiple style="width:100%; padding:8px;">
            <p style="margin:6px 0 0; color:#6b7280; font-size:13px;">You can upload up to 5 files.</p>
        </div>

        <div>
            <label>Phone Number</label><br>
            <div style="display:flex; align-items:center; gap:0; width:100%;">
                <span style="padding:8px 12px; background:#f3f4f6; border:1px solid #d1d5db; border-right:none; border-radius:6px 0 0 6px; color:#374151; white-space:nowrap;">+880</span>
                <input type="text" name="owner_phone" value="{{ old('owner_phone') }}" placeholder="1XXXXXXXXX" required style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:0 6px 6px 0;">
            </div>
        </div>

        <div>
            <label>Location (Optional)</label><br>
            <input type="text" name="location" value="{{ old('location') }}" placeholder="Street, area, landmark" style="width:100%; padding:8px;">
        </div>

        <label style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous'))>
            Post as anonymous (admin can still see your identity)
        </label>

        <button type="submit" style="padding:10px 14px; background:#dc2626; color:#fff; border:none; border-radius:6px; width:max-content;">
            Submit Alert
        </button>
    </form>
</div>
@endsection
