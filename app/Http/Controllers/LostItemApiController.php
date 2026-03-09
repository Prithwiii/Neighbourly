<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LostItem;

class LostItemApiController extends Controller
{
    // Get all lost items
    public function index()
    {
        return response()->json(LostItem::all());
    }

    // Get single lost item
    public function show($id)
    {
        $item = LostItem::findOrFail($id);
        return response()->json($item);
    }

    // Add new lost item
    public function store(Request $request)
    {
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
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/lost_items', $filename);
            $data['image'] = $filename;
        }

        $item = LostItem::create($data);

        return response()->json([
            'message' => 'Lost item added successfully!',
            'data' => $item
        ], 201);
    }

    // Search items
    public function search(Request $request)
    {
        $query = $request->input('query');
        $items = LostItem::where('description', 'like', "%{$query}%")
                         ->orWhere('username', 'like', "%{$query}%")
                         ->get();

        return response()->json($items);
    }
}