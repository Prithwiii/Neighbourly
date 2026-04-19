@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; margin-bottom:16px; flex-wrap:wrap;">
        <h1 style="margin:0;">Emergency Alerts</h1>

        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.emergency-alerts.index') }}"
                   style="display:inline-flex; align-items:center; padding:10px 14px; background:#0f766e; color:#fff; text-decoration:none; border-radius:9999px; font-weight:600; white-space:nowrap;">
                    Review Alerts
                    @if(($newAlertRequests ?? 0) > 0)
                        <span style="display:inline-flex; align-items:center; justify-content:center; min-width:20px; height:20px; margin-left:8px; padding:0 6px; background:#ef4444; color:#fff; border-radius:9999px; font-size:12px; font-weight:700; line-height:1;">
                            {{ $newAlertRequests > 9 ? '9+' : $newAlertRequests }}
                        </span>
                    @endif
                </a>
            @endif

            <a href="{{ route('emergency-alerts.create') }}"
               style="display:inline-flex; align-items:center; padding:10px 14px; background:#dc2626; color:#fff; text-decoration:none; border-radius:9999px; font-weight:600; white-space:nowrap;">
                Create Alert
            </a>
        </div>
    </div>

    @if(session('success'))
        <p style="color:green; margin-bottom:12px;">{{ session('success') }}</p>
    @endif

    @forelse($alerts as $alert)
        <div style="border:1px solid #ddd; border-radius:8px; padding:14px; margin-bottom:12px; background:rgba(255,255,255,0.9);">
            <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <div>
                    <p style="margin:0 0 6px;"><strong>Type:</strong> {{ $alert->alert_type }}</p>
                    <h3 style="margin:0 0 8px;">{{ $alert->title }}</h3>
                    <p style="margin:0 0 8px; color:#374151;">{{ \Illuminate\Support\Str::limit($alert->description, 220) }}</p>

                    @if(! empty($alert->media))
                        @php
                            $firstMedia = $alert->media[0];
                            $firstMime = $firstMedia['mime'] ?? '';
                            $firstUrl = Storage::url($firstMedia['path'] ?? '');
                        @endphp

                        <div style="margin:10px 0 12px; max-width:420px;">
                            @if(str_starts_with($firstMime, 'video/'))
                                <video controls style="width:100%; max-height:260px; border-radius:8px; background:#000;">
                                    <source src="{{ $firstUrl }}" type="{{ $firstMime }}">
                                    Your browser does not support the video tag.
                                </video>
                            @else
                                <img src="{{ $firstUrl }}" alt="Emergency alert media" style="width:100%; max-height:260px; object-fit:cover; border-radius:8px;">
                            @endif
                        </div>
                    @endif

                    <p style="margin:0 0 6px;"><strong>Location:</strong> {{ $alert->location ?: 'Not provided' }}</p>

                    @php
                        $canSeeOwner = ! $alert->is_anonymous || auth()->id() === $alert->user_id || auth()->user()->isAdmin();
                    @endphp

                    <p style="margin:0 0 6px;">
                        <strong>Posted By:</strong>
                        @if($canSeeOwner)
                            {{ $alert->user->name }}
                            @if($alert->is_anonymous && auth()->id() === $alert->user_id)
                                (Anonymous to others)
                            @endif
                        @else
                            Anonymous
                        @endif
                    </p>

                    <p style="margin:0; color:#6b7280; font-size:14px;">{{ $alert->created_at->diffForHumans() }}</p>
                </div>

                <div style="display:flex; flex-direction:column; gap:8px; align-items:flex-end;">
                        @php
                            $canDeleteAlert = auth()->id() === $alert->user_id || (auth()->user()->isAdmin() && $alert->status === 'approved');
                        @endphp

                        @if($canDeleteAlert)
                            <form method="POST" action="{{ route('emergency-alerts.destroy', $alert) }}" onsubmit="return confirm('Delete this emergency alert? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Delete alert" title="Delete alert" style="display:inline-flex; align-items:center; justify-content:center; width:38px; height:38px; background:#b91c1c; color:#fff; border:none; border-radius:9999px; cursor:pointer;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true" focusable="false">
                                        <path d="M9 3a1 1 0 0 0-1 1v1H5a1 1 0 1 0 0 2h1v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7h1a1 1 0 1 0 0-2h-3V4a1 1 0 0 0-1-1H9zm2 2h2v1h-2V5zm-3 2h8v12H8V7zm2 2a1 1 0 0 0-1 1v7a1 1 0 1 0 2 0v-7a1 1 0 0 0-1-1zm4 0a1 1 0 0 0-1 1v7a1 1 0 1 0 2 0v-7a1 1 0 0 0-1-1z"/>
                                    </svg>
                                </button>
                            </form>
                        @endif

                    <a href="{{ route('emergency-alerts.show', $alert) }}" style="text-decoration:none;">
                        <button type="button" style="display:inline-block; min-width:130px; padding:10px 14px; border:none; border-radius:9999px; background:#111827; color:#fff; font-weight:600; cursor:pointer;">
                            View Details
                        </button>
                    </a>

                    @if(! $alert->is_anonymous && auth()->id() !== $alert->user_id)
                        <a href="{{ route('messages.direct.create', $alert->user) }}" style="text-decoration:none;">
                            <button type="button" style="display:inline-block; min-width:130px; padding:10px 14px; border:none; border-radius:9999px; background:#2563eb; color:#fff; font-weight:600; cursor:pointer;">
                                Send Message
                            </button>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p>No approved emergency alerts yet.</p>
    @endforelse

    {{ $alerts->links() }}
</div>
@endsection
