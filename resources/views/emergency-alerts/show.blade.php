@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    @if(session('success'))
        <p style="color:green; margin-bottom:12px;">{{ session('success') }}</p>
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

    <div style="border:1px solid #ddd; border-radius:8px; padding:16px; margin-bottom:16px; background:rgba(255,255,255,0.9);">
        <p><strong>Status:</strong> {{ strtoupper($emergencyAlert->status) }}</p>
        <p><strong>Type:</strong> {{ $emergencyAlert->alert_type }}</p>
        <p><strong>Title:</strong> {{ $emergencyAlert->title }}</p>
        <p><strong>Description:</strong> {{ $emergencyAlert->description }}</p>
        <p><strong>Location:</strong> {{ $emergencyAlert->location ?: 'Not provided' }}</p>

        @if(! empty($emergencyAlert->media))
            <div style="margin:14px 0; display:grid; gap:12px;">
                @foreach($emergencyAlert->media as $attachment)
                    @php
                        $mime = $attachment['mime'] ?? '';
                        $url = Storage::url($attachment['path'] ?? '');
                    @endphp

                    <div style="border:1px solid #e5e7eb; border-radius:8px; padding:10px; background:#fff;">
                        @if(str_starts_with($mime, 'video/'))
                            <video controls style="width:100%; max-height:420px; border-radius:8px;">
                                <source src="{{ $url }}" type="{{ $mime }}">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <img src="{{ $url }}" alt="Emergency alert media" style="width:100%; max-height:420px; object-fit:cover; border-radius:8px;">
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @php
            $canSeeOwner = ! $emergencyAlert->is_anonymous || auth()->id() === $emergencyAlert->user_id || auth()->user()->isAdmin();
        @endphp

        <p>
            <strong>Posted By:</strong>
            @if($canSeeOwner)
                {{ $emergencyAlert->user->name }}
                @if($emergencyAlert->is_anonymous && auth()->id() === $emergencyAlert->user_id)
                    (Anonymous to others)
                @endif
            @else
                Anonymous
            @endif
        </p>

        @if(auth()->user()->isAdmin())
            <p><strong>Owner Email (Admin Only):</strong> {{ $emergencyAlert->user->email }}</p>
            <p><strong>Phone (Admin Only):</strong> {{ $emergencyAlert->owner_phone }}</p>
            @if($emergencyAlert->admin_note)
                <p><strong>Admin Note:</strong> {{ $emergencyAlert->admin_note }}</p>
            @endif
        @endif

        @if($emergencyAlert->isApproved() && ! $emergencyAlert->is_anonymous && auth()->id() !== $emergencyAlert->user_id)
            <a href="{{ route('messages.direct.create', $emergencyAlert->user) }}" style="text-decoration:none;">
                <button type="button" style="padding:10px 14px; border:none; border-radius:9999px; background:#2563eb; color:#fff; font-weight:600; cursor:pointer;">
                    Send Message
                </button>
            </a>
        @endif

        @php
            $canDeleteAlert = auth()->id() === $emergencyAlert->user_id || (auth()->user()->isAdmin() && $emergencyAlert->isApproved());
        @endphp

        @if($canDeleteAlert)
            <form method="POST" action="{{ route('emergency-alerts.destroy', $emergencyAlert) }}" style="margin-top:12px;" onsubmit="return confirm('Delete this emergency alert? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" aria-label="Delete alert" title="Delete alert" style="display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; border:none; border-radius:9999px; background:#b91c1c; color:#fff; cursor:pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true" focusable="false">
                        <path d="M9 3a1 1 0 0 0-1 1v1H5a1 1 0 1 0 0 2h1v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7h1a1 1 0 1 0 0-2h-3V4a1 1 0 0 0-1-1H9zm2 2h2v1h-2V5zm-3 2h8v12H8V7zm2 2a1 1 0 0 0-1 1v7a1 1 0 1 0 2 0v-7a1 1 0 0 0-1-1zm4 0a1 1 0 0 0-1 1v7a1 1 0 1 0 2 0v-7a1 1 0 0 0-1-1z"/>
                    </svg>
                </button>
            </form>
        @endif
    </div>

    @if($emergencyAlert->isApproved())
        <div style="border:1px solid #ddd; border-radius:8px; padding:16px; margin-bottom:16px; background:rgba(255,255,255,0.9);">
            <h3 style="margin-top:0;">Comments</h3>

            @forelse($emergencyAlert->comments as $comment)
                <div style="padding:10px 0; border-bottom:1px solid #eee;">
                    <p style="margin:0 0 4px;"><strong>{{ $comment->user->name }}</strong> <span style="color:#6b7280; font-size:13px;">{{ $comment->created_at->diffForHumans() }}</span></p>
                    <p style="margin:0;">{{ $comment->comment }}</p>
                </div>
            @empty
                <p>No comments yet.</p>
            @endforelse
        </div>

        <div style="border:1px solid #ddd; border-radius:8px; padding:16px; background:rgba(255,255,255,0.9);">
            <h3 style="margin-top:0;">Add Comment</h3>

            <form method="POST" action="{{ route('emergency-alerts.comments.store', $emergencyAlert) }}" style="display:grid; gap:10px;">
                @csrf
                <textarea name="comment" rows="4" required style="width:100%; padding:8px;">{{ old('comment') }}</textarea>
                <button type="submit" style="padding:8px 12px; width:max-content; background:#111827; color:#fff; border:none; border-radius:6px;">
                    Post Comment
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
