<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function toggleTheme()
    {
        $user = auth()->user();
        $user->theme_mode = $user->theme_mode === 'dark' ? 'light' : 'dark';
        $user->save();

        return back();
    }
}
