<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'user_id'];

    // A post can have many images
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    // A post can have many bookmarks
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    // A post can have many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // A post belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Bookmark count for this post
    public function bookmarkCount()
    {
        return $this->bookmarks()->count();
    }

    // Tags for this post (via many-to-many relationship)
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
