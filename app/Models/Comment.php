<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'image_id', 'content'];

    public function likes()
{
    return $this->hasMany(Like::class);
}

public function isLikedBy($user)
{
    return $this->likes->contains('user_id', $user->id);
}

public function image()
{
    return $this->belongsTo(Image::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}

// In Comment model (App\Models\Comment)
public function post()
{
    return $this->belongsTo(Post::class);
}

}
