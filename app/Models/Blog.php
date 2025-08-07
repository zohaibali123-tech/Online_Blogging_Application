<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'blog_title',
        'post_per_page',
        'blog_background_image',
        'blog_status',
        'user_id',
    ];    

    public function posts()
    {
        return $this->hasMany(Post::class, 'blog_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function followers()
    {
        return $this->hasMany(BlogFollower::class, 'blog_following_id')->where('status', 'Followed');
    }
}
