@extends('layouts.app')

@section('content')
<div style="max-width: 760px; margin: 0 auto;">
    <h1>My Provider Application</h1>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if(session('info'))
        <p style="color:#0b5ed7;">{{ session('info') }}</p>
    @endif

    @if(session('otp_demo'))
        <p style="background:#fff3cd; color:#664d03; padding:10px; border-radius:6px;">
            {{ session('otp_demo') }}
        </p>
    @endif

    @if($errors->any())
        <div style="background:#fde2e2; color:#8a1f1f; padding:12px; border-radius:6px; margin-bottom:16px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="border:1px solid #ddd; border-radius:8px; padding:16px; margin-bottom:16px;">
        <p><strong>Name:</strong> {{ $provider->full_name }}</p>
        <p><strong>Phone:</strong> {{ $provider->phone }}</p>
        <p><strong>Category:</strong> {{ $provider->service_category }}</p>
        <p><strong>Location:</strong> {{ $provider->location }}</p>
        <p><strong>Experience:</strong> {{ $provider->experience_years }} years</p>
        <p><strong>Status:</strong> {{ strtoupper($provider->verification_status) }}</p>
        <p><strong>Phone Verified:</strong> {{ $provider->phone_verified_at ? 'Yes' : 'No' }}</p>
        @if($provider->fraud_note)
            <p><strong>Fraud Note:</strong> {{ $provider->fraud_note }}</p>
        @endif
        @if($provider->admin_note)
            <p><strong>Admin Note:</strong> {{ $provider->admin_note }}</p>
        @endif
    </div>

    @if(!$provider->phone_verified_at)
        <div style="border:1px solid #ddd; border-radius:8px; padding:16px; margin-bottom:16px;">
            <h3>Verify Phone (Demo OTP)</h3>
            <form method="POST" action="{{ route('providers.verify-phone', $provider) }}">
                @csrf
                <input type="text" name="otp" maxlength="6" placeholder="Enter 6-digit OTP" required style="padding:8px; width:220px;">
                <button type="submit" style="padding:8px 12px;">Verify</button>
            </form>
        </div>
    @endif

    <div style="border:1px solid #ddd; border-radius:8px; padding:16px;">
        <h3>Update Availability</h3>
        <form method="POST" action="{{ route('providers.update-availability', $provider) }}">
            @csrf
            @method('PATCH')
            <select name="availability_status" style="padding:8px;">
                <option value="available" @selected($provider->availability_status === 'available')>Available</option>
                <option value="busy" @selected($provider->availability_status === 'busy')>Busy</option>
                <option value="offline" @selected($provider->availability_status === 'offline')>Offline</option>
            </select>
            <input type="text" name="availability_details" value="{{ $provider->availability_details }}" placeholder="Optional time slot" style="padding:8px; width:260px;">
            <button type="submit" style="padding:8px 12px;">Save</button>
        </form>
    </div>
</div>
@endsection
