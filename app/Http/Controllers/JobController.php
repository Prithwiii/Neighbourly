<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;

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
            'job_datetime' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $validated['user_id'] = auth()->id();

        Job::create($validated);

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
}
