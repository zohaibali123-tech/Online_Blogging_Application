<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'blog_id',
        'post_title',
        'post_summary',
        'post_discription',
        'featured_image',
        'post_status',
        'is_comment_allowed',
    ];

    // Relationship: Many-to-Many with Category
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_category', 'post_id', 'category_id')->withTimestamps();
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }

    public function attachments()
    {
        return $this->hasMany(PostAttachment::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
