<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostAttachment extends Model
{
    protected $fillable = [
        'post_id',
        'post_attachment_title',
        'post_attachment_path',
        'is_active',
    ];    

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
