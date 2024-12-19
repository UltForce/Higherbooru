<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    // Assuming 'user_id' and 'image_id' are the foreign keys in the 'bookmarks' table
    protected $fillable = ['user_id', 'image_id', "post_id"];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

