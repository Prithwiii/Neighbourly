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
           
            'content' => 'required|string',
           
            'image' => 'nullable|image',
        ]);

        // Get basic data
        $data = [];
        $data['username'] = auth()->user()->name;
      
        $data['location'] = auth()->user()->location;

        // ✅ ADD USER GEO LOCATION (IMPORTANT FIX)
        $data['lat'] = auth()->user()->lat;
        $data['lng'] = auth()->user()->lng;
        $data['content'] = $request->input('content');

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->storeAs('posts', $filename, 'public');

            $data['image'] = 'posts/' . $filename;
        }

        // Save post
        Post::create($data);

        return redirect()->back()->with('success', 'Post shared successfully!');
    }
}