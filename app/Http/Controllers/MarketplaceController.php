<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MarketplaceController extends Controller
{
    /**
     * Show the marketplace feed.
     */
    public function index(Request $request)
    {
        $query = MarketplaceItem::with('user')->orderByDesc('created_at');

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $items = $query->paginate(10);

        return view('marketplace.index', compact('items'));
    }

    /**
     * Show the create listing form.
     */
    public function create()
    {
        return view('marketplace.create');
    }

    /**
     * Store a new listing in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('marketplace', 'public');
            $validated['image'] = $imagePath;
        }

        // Set the authenticated user
        $validated['user_id'] = Auth::id();

        MarketplaceItem::create($validated);

        return redirect()->route('marketplace.index')->with('success', 'Listing created successfully!');
    }

    /**
     * Show a single listing.
     */
    public function show(MarketplaceItem $marketplace)
    {
        return view('marketplace.show', compact('marketplace'));
    }

    /**
     * Show the edit form for the listing.
     */
    public function edit(MarketplaceItem $marketplace)
    {
        // Ensure user owns the item
        if ($marketplace->user_id !== Auth::id()) {
            abort(403);
        }

        return view('marketplace.edit', compact('marketplace'));
    }

    /**
     * Update the listing.
     */
    public function update(Request $request, MarketplaceItem $marketplace)
    {
        // Ensure user owns the item
        if ($marketplace->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($marketplace->image) {
                Storage::disk('public')->delete($marketplace->image);
            }
            $imagePath = $request->file('image')->store('marketplace', 'public');
            $validated['image'] = $imagePath;
        }

        $marketplace->update($validated);

        return redirect()->route('marketplace.show', $marketplace)->with('success', 'Listing updated successfully!');
    }

    /**
     * Delete the listing.
     */
    public function destroy(MarketplaceItem $marketplace)
    {
        // Ensure user owns the item
        if ($marketplace->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete image if exists
        if ($marketplace->image) {
            Storage::disk('public')->delete($marketplace->image);
        }

        $marketplace->delete();

        return redirect()->route('marketplace.index')->with('success', 'Listing deleted successfully!');
    }
}