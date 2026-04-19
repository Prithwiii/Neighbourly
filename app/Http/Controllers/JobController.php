<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use Carbon\Carbon;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::latest()->get();
        return view('jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            // 'job_datetime' => 'required',
            'job_date' => 'required|date',
            'job_time' => 'required',
            'location' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $datetime = \Carbon\Carbon::parse($request
            ->job_date . ' ' . $request->job_time)
            ->format('Y-m-d H:i:s');

        Job::create([
        'name' => $validated['name'],
        'contact' => $validated['contact'],
        'job_datetime' => $datetime,
        'location' => $validated['location'],
        'description' => $validated['description'],
        'user_id' => auth()->id(),
        'status' => 'open', // optional but recommended
        ]);

        return redirect()->route('jobs.index')
            ->with('success', 'Job posted successfully.');
    }

    public function show(Job $job)
    {
        return view('jobs.show', compact('job'));
    }

    public function destroy(Job $job)
    {
        if (auth()->id() !== $job->user_id && !auth()->user()->is_admin) {
            abort(403);
        }

        $job->delete();

        return redirect()->route('jobs.index')
            ->with('success', 'Job deleted.');
    }

    public function selectWorker(Request $request, Job $job)
    {
        $request->validate([
            'enlisting_id' => 'required|exists:enlistings,id'
        ]);

        // Only job owner can select
        if (auth()->id() !== $job->user_id) {
            abort(403);
        }

        // Prevent re-selection if already chosen
        if ($job->selected_enlisting_id) {
            return back()->with('error', 'Worker already selected.');
        }

        $job->selected_enlisting_id = $request->enlisting_id;
        $job->save();

        return back()->with('success', 'Worker selected.');
    }
}
