@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:16px;">
        <h1 style="margin:0;">Emergency Alert Approval Queue</h1>

        <a href="{{ route('emergency-alerts.index') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 14px; background:#0f766e; color:#fff; text-decoration:none; border-radius:9999px; font-weight:600;">
            Review Alerts
            @if(($pendingCount ?? 0) > 0)
                <span style="display:inline-flex; align-items:center; justify-content:center; min-width:20px; height:20px; padding:0 6px; background:#ef4444; color:#fff; border-radius:9999px; font-size:12px; font-weight:700; line-height:1;">
                    {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                </span>
            @endif
        </a>
    </div>

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

    @forelse($pendingAlerts as $alert)
        <div style="border:1px solid #ddd; border-radius:8px; padding:14px; margin-bottom:12px; background:rgba(255,255,255,0.9);">
            <p><strong>Type:</strong> {{ $alert->alert_type }}</p>
            <p><strong>Title:</strong> {{ $alert->title }}</p>
            <p><strong>Description:</strong> {{ $alert->description }}</p>

            @if(! empty($alert->media))
                <div style="margin:12px 0; display:grid; gap:10px;">
                    @foreach($alert->media as $attachment)
                        @php
                            $mime = $attachment['mime'] ?? '';
                            $url = \Illuminate\Support\Facades\Storage::url($attachment['path'] ?? '');
                        @endphp

                        <div style="border:1px solid #e5e7eb; border-radius:8px; padding:8px; background:#fff; max-width:480px;">
                            @if(str_starts_with($mime, 'video/'))
                                <video controls style="width:100%; max-height:280px; border-radius:8px; background:#000;">
                                    <source src="{{ $url }}" type="{{ $mime }}">
                                    Your browser does not support the video tag.
                                </video>
                            @else
                                <img src="{{ $url }}" alt="Emergency alert media" style="width:100%; max-height:280px; object-fit:cover; border-radius:8px;">
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <p><strong>Location:</strong> {{ $alert->location ?: 'Not provided' }}</p>
            <p><strong>Anonymous Public View:</strong> {{ $alert->is_anonymous ? 'Yes' : 'No' }}</p>
            <p><strong>Post Owner (Admin Only):</strong> {{ $alert->user->name }} ({{ $alert->user->email }})</p>
            <p><strong>Phone (Admin Only):</strong> {{ $alert->owner_phone }}</p>

            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:10px; align-items:flex-end;">
                <form method="POST" action="{{ route('admin.emergency-alerts.approve', $alert) }}" style="display:flex; gap:8px; flex-wrap:wrap; align-items:flex-end;">
                    @csrf
                    <div>
                        <label>Optional Note</label><br>
                        <input type="text" name="admin_note" placeholder="Approval note" style="padding:8px; width:240px;">
                    </div>
                    <button type="submit" style="padding:8px 12px; background:#198754; color:#fff; border:none; border-radius:4px;">
                        Approve
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.emergency-alerts.reject', $alert) }}" style="display:flex; gap:8px; flex-wrap:wrap; align-items:flex-end;">
                    @csrf
                    <div>
                        <label>Rejection Reason</label><br>
                        <input type="text" name="admin_note" placeholder="Reason for rejection" required style="padding:8px; width:260px;">
                    </div>
                    <button type="submit" style="padding:8px 12px; background:#dc3545; color:#fff; border:none; border-radius:4px;">
                        Reject
                    </button>
                </form>
            </div>
        </div>
    @empty
        <p>No pending emergency alerts right now.</p>
    @endforelse

    {{ $pendingAlerts->links() }}
</div>
@endsection
