<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Create a new post
    public function store(Request $request)
    {
        $validated = $request->validate([
            "title" => "required|string",
            "content" => "required|string",
        ]);

        $post = Post::create([
            "title" => $validated["title"],
            "content" => $validated["content"],
        ]);

        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'created_at' => $post->created_at
        ]);
    }

    // List all posts
    public function index()
    {
        $posts = Post::all(['id', 'title', 'content', 'created_at']);
        return response()->json($posts);
    }

    // View a single post
    public function show($id)
    {
        $post = Post::findOrFail($id, ['id', 'title', 'content', 'created_at']);
        return response()->json($post);
    }

    // Update a post
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            "title" => "required|string",
            "content" => "required|string",
        ]);

        $post = Post::findOrFail($id);
        $post->title = $validated["title"];
        $post->content = $validated["content"];
        $post->save();

        return response()->json($post);
    }

    // Delete a post
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(["message" => "Post deleted successfully"]);
    }
}