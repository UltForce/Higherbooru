<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Bookmark;
use App\Models\Tag;
use App\Models\Like;
use App\Models\Comment;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $query = Image::query();
    
        // Exclude images owned by the logged-in user, except for admins
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', '!=', auth()->id());
        }
    
        // Search by title
        if ($request->has('title') && $request->input('title') !== '') {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }
    
        // Search by tags
        if ($request->has('tags') && $request->input('tags') !== '') {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('tags') . '%');
            });
        }
    
        // Apply sorting based on user selection
        if ($request->has('sort')) {
            if ($request->input('sort') == 'newest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->input('sort') == 'oldest') {
                $query->orderBy('created_at', 'asc');
            }
        }
    
        // Paginate results
        $images = $query->paginate(10);
    
        // Add 'is_bookmarked' property to each image
        $user = auth()->user();
        foreach ($images as $image) {
            // Check if the user has bookmarked the image
            $image->is_bookmarked = $user ? $user->bookmarks()->where('image_id', $image->id)->exists() : false;
        }
    
        // Return the view with the filtered results
        return view('images.index', compact('images'));
    }
    
    
    
    
    
public function myImages()
{
    // Show only images owned by the logged-in user
    $images = Image::where('user_id', auth()->id())->get();

    return view('images.my-images', compact('images'));
}

public function create()
{
    // Fetch existing tags from the database
    $tags = Tag::all();
    return view('images.create', compact('tags'));
}


public function store(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'title' => 'required|string|max:255',
        'image' => 'required|image',
        'tags' => 'nullable|array', // Array of tags
        'tags.*' => 'nullable|string|distinct', // Each tag should be a string and unique
    ]);

    // Create the new image
    $image = new Image();
    $image->title = $request->input('title');
    $image->image_path = $request->file('image')->store('images', 'public');
    $image->user_id = auth()->id();
    $image->save();

    // Handle tags (existing and new)
    $tags = $request->input('tags', []);
    
    // Loop through the tags and either create new ones or associate existing ones
    foreach ($tags as $tagName) {
        // Trim and check if the tag exists or create it
        $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
        $image->tags()->attach($tag->id); // Assuming you have a many-to-many relationship
    }

    return redirect()->route('images.index')->with('success', 'Image created successfully!');
}
    
    

public function show($id)
{
    $image = Image::with(['tags', 'comments.likes'])->findOrFail($id);
    return view('images.show', compact('image'));
}


    public function edit(Image $image)
    {
        if (!auth()->user()->isAdmin() && auth()->id() !== $image->user_id) {
            abort(403); // Forbidden
        }
        return view('images.edit', compact('image'));
    }
    
    public function update(Request $request, Image $image)
    {
        if (!auth()->user()->isAdmin() && auth()->id() !== $image->user_id) {
            abort(403); // Forbidden
        }
    
        $request->validate([
            'title' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
            $image->image_path = $imagePath;
        }
    
        $image->title = $request->title;
        $image->save();
    
        return redirect()->route('images.my-images')->with('success', 'Image updated successfully');
    }
    
    public function destroy(Image $image)
{
    if (!auth()->user()->isAdmin() && auth()->id() !== $image->user_id) {
        abort(403); // Forbidden
    }

    $image->delete();
    return redirect()->route('images.my-images')->with('success', 'Image deleted successfully');
}


public function bookmark($imageId)
{
    $user = auth()->user();
    $image = Image::findOrFail($imageId);

    // Check if the user has already bookmarked this image
    $existingBookmark = Bookmark::where('user_id', $user->id)
                                ->where('image_id', $image->id)
                                ->first();

    if ($existingBookmark) {
        // If already bookmarked, delete the bookmark
        $existingBookmark->delete();
        return back()->with('message', 'Bookmark removed.');
    } else {
        // If not bookmarked, create a new bookmark
        Bookmark::create([
            'user_id' => $user->id,
            'image_id' => $image->id,
        ]);
        return back()->with('message', 'Image bookmarked!');
    }
}

public function myBookmarks()
{
    $user = auth()->user();
    $bookmarkedImages = $user->bookmarks()->with('image')->get()->pluck('image');


    return view('images.my-bookmarks', compact('bookmarkedImages'));
}


}