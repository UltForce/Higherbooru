<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['image_path', 'post_id'];

    // An image belongs to a post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // An image belongs to a user (optional if required)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
