<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enlisting;

class EnlistingController extends Controller
{
    public function index()
    {
        $enlistings = Enlisting::latest()->get();
        return view('enlistings.index', compact('enlistings'));
    }

    public function create()
    {
        return view('enlistings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'preferred_job' => 'required|string|max:255',
            'availability' => 'required'
        ]);

        $validated['user_id'] = auth()->id();

        Enlisting::create($validated);

        return redirect()->route('enlistings.index')
            ->with('success', 'Enlisting created successfully.');
    }

    public function show(Enlisting $enlisting)
    {
        return view('enlistings.show', compact('enlisting'));
    }

    public function destroy(Enlisting $enlisting)
    {
        // Admin or owner
        if (auth()->id() !== $enlisting->user_id && !auth()->user()->is_admin) {
            abort(403);
        }

        $enlisting->delete();

        return redirect()->route('enlistings.index')
            ->with('success', 'Enlisting deleted.');
    }
}