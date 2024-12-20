<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['user_id', 'comment_id'];


    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
