<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',  // Title is required, must be a string, and max 255 characters
            'content' => 'required|string',        // Content is required and must be a string
        ]);

        // If validation fails, return JSON response with validation error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create a new post using the validated request data
        $post = Post::create($request->all());

        // Return success response with created post data
        return response()->json([
            'status' => 'success',
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }

    public function index()
    {
        // Fetch all posts from the database and return as JSON response
        return response()->json(Post::all());
    }

    public function show($id)
    {
        // Fetch a specific post by ID, if not found, return 404 error automatically
        return response()->json(Post::findOrFail($id));
    }
}
