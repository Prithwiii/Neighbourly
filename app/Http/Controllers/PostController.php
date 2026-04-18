<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    // Show all posts
    public function index()
    {
        $posts = Post::latest()->get();

        return view('posts.index', compact('posts'));
    }

    // Store new post
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'username' => 'required|string|max:255',
            'content' => 'required|string',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image',
        ]);

        // Get basic data
        $data = $request->only(['username', 'content', 'location']);

        // ✅ ADD USER GEO LOCATION (IMPORTANT FIX)
        $data['lat'] = auth()->user()->lat;
        $data['lng'] = auth()->user()->lng;

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->storeAs('public/posts', $filename);

            $data['image'] = 'posts/' . $filename;
        }

        // Save post
        Post::create($data);

        return redirect()->back()->with('success', 'Post shared successfully!');
    }
}