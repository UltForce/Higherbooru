<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    // Store a comment for a post
    public function store(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:255', // Adjust validation if needed
        ]);

        // Find the post by ID
        $post = Post::findOrFail($postId);

        // Create a new comment associated with the post
        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->input('content'), // Ensure 'content' is provided
        ]);

        return redirect()->back()->with('success', 'Comment posted successfully.');
    }

    // Like or unlike a comment
    public function like($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $user = auth()->user();

        if ($comment->likes()->where('user_id', $user->id)->exists()) {
            $comment->likes()->where('user_id', $user->id)->delete();
        } else {
            $comment->likes()->create(['user_id' => $user->id]);
        }

        return redirect()->back();
    }

    // Delete a comment
    public function destroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        // Check if the authenticated user is the owner or an admin
        if (auth()->id() !== $comment->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}
