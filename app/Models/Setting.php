<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'site_logo',
        'contact_email',
        'facebook_link',
        'twitter_link',
        'instagram_link',
        'theme_mode',
        'footer_description',
    ];
}
