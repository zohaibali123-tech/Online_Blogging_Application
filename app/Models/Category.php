<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'category_title',
        'category_description',
        'category_status',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_category', 'category_id', 'post_id')->withTimestamps();
    }
}
