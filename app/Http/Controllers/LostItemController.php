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
        // Validate input
        $request->validate([
            
            'phone' => 'required|string|max:50',
            'description' => 'required|string',
            
            'date_lost' => 'required|date',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [];
        $data['username'] = auth()->user()->name;
        $data['location'] = auth()->user()->location ?? 'Unknown';
        $data['phone'] = $request->phone;
        $data['description'] = $request->description;
        $data['date_lost'] = $request->date_lost;


        // IMAGE UPLOAD FIXED
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $filename = time() . '_' . $file->getClientOriginalName();

            // store in: storage/app/public/lost-items
            $file->storeAs('lost-items', $filename, 'public');

            // save path in DB
            $data['image'] = 'lost-items/' . $filename;
        }

        LostItem::create($data);

        return redirect('/lost-items')
            ->with('success', 'Lost item reported successfully!');
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