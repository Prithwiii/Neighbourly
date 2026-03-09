<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\IssueVote;
use App\Models\FakeReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IssueController extends Controller
{
    /**
     * Show the issue feed.
     */
    public function index()
    {
        $issues = Issue::with('user', 'votes', 'fakeReports')
            ->withCount('votes', 'fakeReports')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('issues.index', compact('issues'));
    }

    /**
     * Show the create issue form.
     */
    public function create()
    {
        $categories = [
            'Road Damage',
            'Electricity Problem',
            'Water Supply Issue',
            'Crime / Suspicious Activity',
            'Garbage / Waste',
            'Environment Problem',
            'Government Service Issue',
            'Other',
        ];

        return view('issues.create', compact('categories'));
    }

    /**
     * Store a new issue in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'category' => 'required|string|in:Road Damage,Electricity Problem,Water Supply Issue,Crime / Suspicious Activity,Garbage / Waste,Environment Problem,Government Service Issue,Other',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'severity' => 'nullable|string|in:low,medium,high,critical',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('issues', 'public');
            $validated['image'] = $imagePath;
        }

        // Set the authenticated user as the reporter
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'under_review';

        Issue::create($validated);

        return redirect()->route('issues.index')->with('success', 'Issue reported successfully!');
    }

    /**
     * Vote/upvote an issue.
     */
    public function vote(Issue $issue)
    {
        $userId = Auth::id();

        // Check if user has already voted
        $existingVote = IssueVote::where('issue_id', $issue->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingVote) {
            // Remove the vote if already exists (toggle)
            $existingVote->delete();
            return back()->with('info', 'Vote removed.');
        } else {
            // Add the vote
            IssueVote::create([
                'issue_id' => $issue->id,
                'user_id' => $userId,
            ]);
            return back()->with('success', 'Thanks for upvoting!');
        }
    }

    /**
     * Report an issue as fake.
     */
    public function reportFake(Issue $issue)
    {
        $userId = Auth::id();

        // Check if user has already reported as fake
        $existingReport = FakeReport::where('issue_id', $issue->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingReport) {
            // Remove the report if already exists (toggle)
            $existingReport->delete();
            $message = 'Report removed.';
        } else {
            // Add the fake report
            FakeReport::create([
                'issue_id' => $issue->id,
                'user_id' => $userId,
            ]);
            $message = 'Thanks for reporting! We review these.';

            // Check if issue should be flagged
            $issue->updateStatusBasedOnReports();
        }

        return back()->with('info', $message);
    }

    /**
     * Verify an issue (admin only).
     */
    public function verify(Issue $issue)
    {
        // Check if user is admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $issue->markAsVerified();

        return back()->with('success', 'Issue marked as verified.');
    }
}
