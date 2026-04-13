@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <h1>Provider Verification Queue</h1>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if(session('info'))
        <p style="color:#0b5ed7;">{{ session('info') }}</p>
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

    @forelse($pendingProviders as $provider)
        <div style="border:1px solid #ddd; border-radius:8px; padding:14px; margin-bottom:12px;">
            <p><strong>Name:</strong> {{ $provider->full_name }}</p>
            <p><strong>User Account:</strong> {{ $provider->user->name }} ({{ $provider->user->email }})</p>
            <p><strong>Phone:</strong> {{ $provider->phone }} | <strong>Phone Verified:</strong> {{ $provider->phone_verified_at ? 'Yes' : 'No' }}</p>
            <p><strong>Category:</strong> {{ $provider->service_category }} | <strong>Location:</strong> {{ $provider->location }}</p>
            <p><strong>Experience:</strong> {{ $provider->experience_years }} years</p>
            <p><strong>Status:</strong> {{ strtoupper($provider->verification_status) }}</p>
            @if($provider->fraud_note)
                <p style="color:#b02a37;"><strong>Fraud Signal:</strong> {{ $provider->fraud_note }}</p>
            @endif
            <p><strong>Description:</strong> {{ $provider->description }}</p>
            @if($provider->document_path)
                <p><a href="{{ asset('storage/'.$provider->document_path) }}" target="_blank">View Uploaded Document</a></p>
            @endif

            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;">
                <form method="POST" action="{{ route('admin.providers.approve', $provider) }}">
                    @csrf
                    <button type="submit" style="padding:8px 12px; background:#198754; color:#fff; border:none; border-radius:4px;">Approve</button>
                </form>

                <form method="POST" action="{{ route('admin.providers.reject', $provider) }}" style="display:flex; gap:8px;">
                    @csrf
                    <input type="text" name="admin_note" placeholder="Reason for rejection" required style="padding:8px; width:280px;">
                    <button type="submit" style="padding:8px 12px; background:#dc3545; color:#fff; border:none; border-radius:4px;">Reject</button>
                </form>
            </div>
        </div>
    @empty
        <p>No pending or flagged provider applications right now.</p>
    @endforelse

    {{ $pendingProviders->links() }}
</div>
@endsection
