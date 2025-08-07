<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogFollower extends Model
{
    protected $table = 'following_blog';

    protected $fillable = [
        'follower_id',
        'blog_following_id',
        'status',
    ];

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_following_id');
    }
}
