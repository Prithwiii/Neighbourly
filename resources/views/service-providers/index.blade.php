@extends('layouts.app')

@section('content')
<div style="max-width: 950px; margin: 0 auto; position: relative;">
    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; margin-bottom:16px;">
        <h1 style="margin:0;">Find Local Service Providers</h1>

        @if(auth()->check() && auth()->user()->isAdmin())
            <a href="{{ route('admin.providers.index') }}"
               style="display:inline-flex; align-items:center; padding:10px 14px; background:#0f766e; color:#fff; text-decoration:none; border-radius:9999px; font-weight:600; box-shadow:0 6px 18px rgba(0,0,0,0.15); white-space:nowrap;">
                Review Provider Applications
                @if(($newProviderRequests ?? 0) > 0)
                    <span style="display:inline-flex; align-items:center; justify-content:center; min-width:20px; height:20px; margin-left:8px; padding:0 6px; background:#ef4444; color:#fff; border-radius:9999px; font-size:12px; font-weight:700; line-height:1;">
                        {{ $newProviderRequests > 9 ? '9+' : $newProviderRequests }}
                    </span>
                @endif
            </a>
        @else
            <a href="{{ auth()->check() ? route('providers.application') : route('login') }}"
               style="display:inline-flex; align-items:center; padding:10px 14px; background:#16a34a; color:#fff; text-decoration:none; border-radius:9999px; font-weight:600; box-shadow:0 6px 18px rgba(0,0,0,0.15); white-space:nowrap;">
                Join as Service Provider
            </a>
        @endif
    </div>

    <form method="GET" action="{{ route('services.index') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:16px;">
        <input type="text" name="category" value="{{ request('category') }}" placeholder="Category (Plumber, Electrician...)" style="padding:8px; width:230px;">
        <input type="text" name="location" value="{{ request('location') }}" placeholder="Location" style="padding:8px; width:220px;">
        <button type="submit" style="padding:8px 12px;">Search</button>
        @if(request()->filled('category') || request()->filled('location'))
            <a href="{{ route('services.index') }}" style="padding:8px 12px; border:1px solid #ccc; text-decoration:none;">Clear</a>
        @endif
    </form>

    @forelse($providers as $provider)
        <div style="border:1px solid #ddd; border-radius:8px; padding:14px; margin-bottom:12px;">
            <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <div>
                    <h3 style="margin:0;">{{ $provider->full_name }} <span style="color:green;">✅ Verified</span></h3>
                    <p style="margin:6px 0;">{{ $provider->service_category }} | {{ $provider->location }}</p>
                    <p style="margin:6px 0;">Experience: {{ $provider->experience_years }} years</p>
                    <p style="margin:6px 0;">Availability: {{ ucfirst($provider->availability_status) }} {{ $provider->availability_details ? '- '.$provider->availability_details : '' }}</p>
                    <p style="margin:6px 0;">Rating: {{ $provider->averageRating() }} / 5 ({{ $provider->reviews_count }} reviews)</p>
                </div>
                <div>
                    <a href="{{ route('services.show', $provider) }}" style="text-decoration:none;">
                        <button type="button" style="display:inline-block; min-width:140px; padding:10px 14px; border:none; border-radius:9999px; background:#0f766e; color:#fff; font-weight:600; cursor:pointer; box-shadow:0 6px 18px rgba(0,0,0,0.15);">
                            View Profile
                        </button>
                    </a>
                    @if(auth()->check() && auth()->id() !== $provider->user_id)
                        <div style="margin-top:10px; text-align:right;">
                            <a href="{{ route('messages.provider.create', $provider) }}" style="text-decoration:none;">
                                <button type="button" style="display:inline-block; min-width:140px; padding:10px 14px; border:none; border-radius:9999px; background:#2563eb; color:#fff; font-weight:600; cursor:pointer; box-shadow:0 6px 18px rgba(0,0,0,0.15);">
                                    Send Message
                                </button>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p>No verified providers found yet.</p>
    @endforelse

    {{ $providers->links() }}
</div>
@endsection
