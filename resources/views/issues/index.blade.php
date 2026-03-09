@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="margin: 0;">Community Issues</h1>
        <a href="{{ route('issues.create') }}" style="background-color: #007bff; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            ➕ Report an Issue
        </a>
    </div>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 12px 20px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div style="background-color: #d1ecf1; color: #0c5460; padding: 12px 20px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('info') }}
        </div>
    @endif

    @forelse($issues as $issue)
        <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <!-- Header with title and category -->
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <h2 style="margin: 0 0 8px 0; color: #333;">{{ $issue->title }}</h2>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                        <span style="background-color: #e7f3ff; color: #0066cc; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                            🏷 {{ $issue->category }}
                        </span>
                        <span style="background-color: #f0f0f0; color: #666; padding: 4px 10px; border-radius: 20px; font-size: 12px;">
                            📍 {{ $issue->location }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <p style="color: #555; margin: 15px 0; line-height: 1.6;">
                {{ Str::limit($issue->description, 300) }}
            </p>

            <!-- Image if available -->
            @if($issue->image)
                <div style="margin: 15px 0;">
                    <img src="{{ asset('storage/' . $issue->image) }}" alt="Issue photo" style="max-width: 100%; border-radius: 5px; max-height: 300px;">
                </div>
            @endif

            <!-- Meta information -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin: 15px 0; padding: 10px 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee; font-size: 14px; color: #666;">
                <div>
                    <strong>👤 {{ $issue->user->name }}</strong> | 
                    <span>🕒 {{ $issue->created_at->diffForHumans() }}</span>
                    @if($issue->severity)
                        | <span style="color: #d9534f; font-weight: bold;">⚠ {{ ucfirst($issue->severity) }} Severity</span>
                    @endif
                </div>
                <div style="background-color: #f9f9f9; padding: 5px 10px; border-radius: 5px;">
                    <span style="font-weight: bold;">{{ $issue->votes_count }}</span> people affected
                </div>
            </div>

            <!-- Action buttons -->
            <div style="display: flex; gap: 10px; margin-top: 15px;">
                @php
                    $userHasVoted = $issue->votes->where('user_id', auth()->id())->count() > 0;
                    $userHasReported = $issue->fake_reports->where('user_id', auth()->id())->count() > 0;
                @endphp

                <form method="POST" action="{{ route('issues.vote', $issue->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background-color: {{ $userHasVoted ? '#28a745' : '#f0f0f0' }}; color: {{ $userHasVoted ? '#fff' : '#333' }}; border: 1px solid {{ $userHasVoted ? '#28a745' : '#ddd' }}; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; transition: all 0.3s;">
                        👍 {{ $userHasVoted ? 'Upvoted' : 'Upvote' }} ({{ $issue->votes_count }})
                    </button>
                </form>

                <form method="POST" action="{{ route('issues.report-fake', $issue->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background-color: {{ $userHasReported ? '#dc3545' : '#f0f0f0' }}; color: {{ $userHasReported ? '#fff' : '#333' }}; border: 1px solid {{ $userHasReported ? '#dc3545' : '#ddd' }}; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; transition: all 0.3s;">
                        🚩 {{ $userHasReported ? 'Reported' : 'Report Fake' }} ({{ $issue->fake_reports_count }})
                    </button>
                </form>

                @if($issue->status === 'resolved')
                    <span style="background-color: #d4edda; color: #155724; padding: 8px 15px; border-radius: 5px; font-weight: bold;">
                        ✓ Resolved
                    </span>
                @elseif($issue->status === 'open')
                    <span style="background-color: #fff3cd; color: #856404; padding: 8px 15px; border-radius: 5px; font-weight: bold;">
                        ⏳ Open
                    </span>
                @endif
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 40px; color: #999;">
            <p style="font-size: 18px;">No issues reported yet.</p>
            <p>Be the first to <a href="{{ route('issues.create') }}" style="color: #007bff; text-decoration: none; font-weight: bold;">report an issue!</a></p>
        </div>
    @endforelse

    <!-- Pagination -->
    <div style="margin-top: 30px;">
        {{ $issues->links() }}
    </div>
</div>
@endsection
