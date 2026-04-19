<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobConfirmation;

class JobConfirmationController extends Controller
{
    public function confirm(Request $request, Job $job)
    {
        if (!$job->selected_enlisting_id) {
            return back()->with('error', 'No worker selected.');
        }

        $confirmation = \App\Models\JobConfirmation::firstOrCreate([
            'job_id' => $job->id,
            'enlisting_id' => $job->selected_enlisting_id,
        ]);

        if (auth()->id() === $job->user_id) {
            $confirmation->employer_confirmed = true;
        }

        elseif (auth()->id() === $job->selectedEnlisting->user_id) {
            $confirmation->worker_confirmed = true;
        }
        else {
            abort(403, 'Unauthorized');
        }

        $confirmation->save();

        if ($confirmation->employer_confirmed && $confirmation->worker_confirmed) {
            $job->status = 'confirmed';
            $job->save();
        }

        return back()->with('success', 'Confirmation updated.');
    }
}