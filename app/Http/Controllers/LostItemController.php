<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use Illuminate\Http\Request;

class LostItemController extends Controller
{
    public function index()
    {
        $items = LostItem::all();
        return view('lost-items.index', compact('items'));
    }

    public function create()
    {
        return view('lost-items.create');
    }

    public function store(Request $request)
    {
    // Validate the required fields
      $request->validate([
        'username' => 'required|string|max:255',
        'phone' => 'required|string|max:50',
        'description' => 'required|string',
        'location' => 'required|string|max:255',
        'date_lost' => 'required|date',
        'image' => 'nullable|image', 
      ]);

      $data = $request->all();

      if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();

        // Ensure folder exists
        $destinationPath = storage_path('app/public/lost_items');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Store the file
        $file->storeAs('public/lost_items', $filename);

        // Save filename to database
        $data['image'] = $filename;
     }

    // Save to database
     LostItem::create($data);

     return redirect('/lost-items')->with('success', 'Lost item reported successfully!');
    }

    public function show(LostItem $lostItem)
    {
        return view('lost-items.show', compact('lostItem'));
    }

    public function edit(LostItem $lostItem)
    {
        //
    }

    public function update(Request $request, LostItem $lostItem)
    {
        //
    }

    public function destroy(LostItem $lostItem)
    {
        //
    }
    public function search(Request $request)
    {
        $query = $request->input('query');

        $items = LostItem::where('description', 'like', "%{$query}%")
                ->orWhere('username', 'like', "%{$query}%")
                ->get();

         return view('lost-items.index', compact('items'));
    }
}