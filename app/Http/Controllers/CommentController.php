<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Bookmark;
use App\Models\Tag;
use App\Models\Like;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:255', // Adjust as necessary
        ]);
        $image = Image::findOrFail($id);

        $image->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->input('content'), // Ensure 'content' is provided
        ]);

        return redirect()->back()->with('success', 'Comment posted successfully.');
    }

    public function like($id)
    {
        $comment = Comment::findOrFail($id);
        $user = auth()->user();

        if ($comment->likes()->where('user_id', $user->id)->exists()) {
            $comment->likes()->where('user_id', $user->id)->delete();
        } else {
            $comment->likes()->create(['user_id' => $user->id]);
        }

        return redirect()->back();
    }

    public function destroy($id)
{
    $comment = Comment::findOrFail($id);

    // Check if the authenticated user is the owner or an admin
    if (auth()->id() !== $comment->user_id && !auth()->user()->isAdmin()) {
        abort(403, 'Unauthorized action.');
    }

    $comment->delete();

    return redirect()->back()->with('success', 'Comment deleted successfully.');
}


}

