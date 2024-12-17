<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['title', 'image_path', 'user_id'];

    public function bookmarks()
{
    return $this->hasMany(Bookmark::class);
}

public function bookmarkCount()
{
    return $this->bookmarks()->count();
}

public function tags()
{
    return $this->belongsToMany(Tag::class);
}

public function comments()
{
    return $this->hasMany(Comment::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}

}

