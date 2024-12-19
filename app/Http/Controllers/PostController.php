<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Image;
use App\Models\Bookmark;
use App\Models\Tag;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query();
    
        // Exclude posts owned by the logged-in user, except for admins
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', '!=', auth()->id());
        }
    
        // Search by title
        if ($request->has('title') && $request->input('title') !== '') {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        // Search by tags
        if ($request->has('tags') && !empty($request->input('tags'))) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('tags') . '%');
            });
        }

        // Apply sorting based on user selection or default to 'newest'
        $sort = $request->input('sort', 'newest'); // Default to 'newest' if no sort parameter is given
        if ($sort == 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        }

        // Eager load the user relationship and paginate the results
        $posts = $query->with(['user', 'images'])->paginate(6); // Ensure pagination is after sorting

        // Add 'is_bookmarked' property to each post
        $user = auth()->user();
        foreach ($posts as $post) {
            $post->is_bookmarked = $user ? $user->bookmarks()->where('post_id', $post->id)->exists() : false;
        }

        // Return the view with the filtered results and sorting options
        return view('posts.index', compact('posts', 'sort'));
    }

    public function myPosts(Request $request)
    {
        $query = Post::where('user_id', auth()->id());

        // Search by title
        if ($request->has('title') && $request->input('title') !== '') {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        // Search by tags
        if ($request->has('tags') && !empty($request->input('tags'))) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('tags') . '%');
            });
        }

        // Apply sorting based on user selection or default to 'newest'
        $sort = $request->input('sort', 'newest'); // Default to 'newest' if no sort parameter is given
        if ($sort == 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        }

        // Eager load the user relationship and paginate the results
        $posts = $query->with(['user', 'images'])->paginate(6); // Ensure pagination is after sorting

        // Add 'is_bookmarked' property to each post
        $user = auth()->user();
        foreach ($posts as $post) {
            $post->is_bookmarked = $user ? $user->bookmarks()->where('post_id', $post->id)->exists() : false;
        }

        // Return the view with the filtered results and sorting options
        return view('posts.my-posts', compact('posts', 'sort'));
    }

    public function create()
    {
        // Fetch existing tags from the database
        $tags = Tag::all();
        return view('posts.create', compact('tags'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'title' => 'required|string|max:32',
            'images.*' => 'required|image',
            'description' => 'nullable|string',
            'tags' => 'nullable|array', // Array of tags
            'tags.*' => 'nullable|string|distinct', // Each tag should be a string and unique
        ]);

        // Create the new post
        $post = new Post();
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->user_id = auth()->id();
        $post->save();

        // Handle image uploads
if ($request->hasFile('images')) {
    foreach ($request->file('images') as $imageFile) {
        $image = new Image();
        $image->image_path = $imageFile->store('images', 'public');
        $image->post_id = $post->id;
        $image->user_id = auth()->id(); // Associate the image with the logged-in user
        $image->save();
    }
}


        // Handle tags (existing and new)
        $tags = $request->input('tags', []);
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
            $post->tags()->attach($tag->id);
        }

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    public function show($id)
    {
        $post = Post::with('images')->findOrFail($id); // Eager load images with the post
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        if (!auth()->user()->isAdmin() && auth()->id() !== $post->user_id) {
            abort(403); // Forbidden
        }

        $tags = Tag::all();
        $selectedTags = $post->tags->pluck('id')->toArray();

        return view('posts.edit', compact('post', 'tags', 'selectedTags'));
    }

    public function update(Request $request, Post $post)
    {
        // Authorization check
        if (!auth()->user()->isAdmin() && auth()->id() !== $post->user_id) {
            abort(403); // Forbidden
        }
    
        // Validate the incoming request
        $request->validate([
            'title' => 'required|max:32',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Update the post fields
        $post->title = $request->title;
        $post->description = $request->description;
        $post->save();
    
        // Delete old images
        foreach ($post->images as $image) { // Assuming $post->images is a relationship
            Storage::delete('public/' . $image->image_path); // Delete from storage
            $image->delete(); // Delete from database
        }
    
        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $image = new Image();
                $image->image_path = $imageFile->store('uploads', 'public');
                $image->post_id = $post->id;
                $image->user_id = auth()->id();
                $image->save();
            }
        }
    
        return redirect()->route('posts.my-posts')->with('success', 'Post updated successfully');
    }
    

    public function destroy(Post $post)
    {
        if (!auth()->user()->isAdmin() && auth()->id() !== $post->user_id) {
            abort(403); // Forbidden
        }

        $post->delete();
        return redirect()->route('posts.my-posts')->with('success', 'Post deleted successfully');
    }

    public function bookmark($postId)
    {
        $user = auth()->user();
        $post = Post::findOrFail($postId);

        $existingBookmark = Bookmark::where('user_id', $user->id)
                                    ->where('post_id', $post->id)
                                    ->first();

        if ($existingBookmark) {
            $existingBookmark->delete();
            $message = 'Post unbookmarked successfully.';
            return back()->with('bookmark_success', $message);
            
        } else {
            Bookmark::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
            $message = 'Post bookmarked successfully.';
            
            return back()->with('bookmark_success', $message);
        }
    }

    public function myBookmarks(Request $request)
    {
        $user = auth()->user();
        $query = Post::whereIn('id', $user->bookmarks()->pluck('post_id'));

        // Search by title
        if ($request->has('title') && $request->input('title') !== '') {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        // Search by tags
        if ($request->has('tags') && !empty($request->input('tags'))) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('tags') . '%');
            });
        }

        $sort = $request->input('sort', 'newest');
        if ($sort == 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        }

        $bookmarkedPosts = $query->with(['user', 'images'])->paginate(6);
        foreach ($bookmarkedPosts as $post) {
            $post->is_bookmarked = true;
        }

        return view('posts.my-bookmarks', compact('bookmarkedPosts', 'sort'));
    }
}
