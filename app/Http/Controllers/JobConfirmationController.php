<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobConfirmation;

class JobConfirmationController extends Controller
{
    public function confirm(Request $request, Job $job)
    {
        $request->validate([
            'enlisting_id' => 'required|exists:enlistings,id'
        ]);

        $enlisting = \App\Models\Enlisting::findOrFail($request->enlisting_id);

        // SECURITY: Only allow owner of enlisting OR job owner
        if (
            auth()->id() !== $job->user_id &&
            auth()->id() !== $enlisting->user_id
        ) {
            abort(403, 'Unauthorized action.');
        }

        $confirmation = \App\Models\JobConfirmation::firstOrCreate([
            'job_id' => $job->id,
            'enlisting_id' => $enlisting->id
        ]);

        // Employer confirms
        if (auth()->id() === $job->user_id) {
            if (!$confirmation->employer_confirmed) {
                $confirmation->employer_confirmed = true;
            }
        }

        // Worker confirms
        if (auth()->id() === $enlisting->user_id) {
            if (!$confirmation->worker_confirmed) {
                $confirmation->worker_confirmed = true;
            }
        }

        $confirmation->save();

        // Finalize job
        if ($confirmation->employer_confirmed && $confirmation->worker_confirmed) {
            $job->status = 'confirmed';
            $job->save();
        }

        return back()->with('success', 'Confirmation updated.');
    }
}