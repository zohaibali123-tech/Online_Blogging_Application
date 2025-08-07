<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('user.profile.view', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('user.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'email'          => 'required|email|unique:users,email,' . $user->id,
            'gender'         => 'required|in:male,female,other',
            'date_of_birth'  => 'required|date',
            'address'        => 'nullable|string|max:255',
            'user_image'     => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('user_image')) {
            if ($user->user_image) {
                Storage::disk('public')->delete($user->user_image);
            }

            $validated['user_image'] = $request->file('user_image')->store('profiles', 'public');
        }

        $user->update($validated);

        return redirect()->route('user.profile.show')->with('success', 'Profile updated successfully.');
    }

}
