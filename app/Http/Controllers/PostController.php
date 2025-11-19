<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Create post
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'nullable|string',
            'company_id'  => 'nullable|exists:companies,id',
        ]);

        $post = Post::create([
            'user_id'     => $request->user()->id,
            'company_id'  => $request->company_id,
            'title'       => $request->title,
            'content'     => $request->content,
        ]);

        return response()->json($post, 201);
    }

    // Update
    public function update(Request $request, $id)
    {
        $post = Post::where('user_id', $request->user()->id)->findOrFail($id);

        $post->update($request->only('title', 'content', 'company_id'));

        return response()->json($post);
    }

    // Delete
    public function destroy(Request $request, $id)
    {
        $post = Post::where('user_id', $request->user()->id)->findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }

    // All posts
    public function index()
    {
        return response()->json(Post::with('user', 'company')->latest()->get());
    }

    // Search posts
    public function search(Request $request)
    {
        $q = $request->query('q');

        $posts = Post::where('title', 'LIKE', "%$q%")
            ->orWhere('content', 'LIKE', "%$q%")
            ->with('user', 'company')
            ->get();

        return response()->json($posts);
    }
}
