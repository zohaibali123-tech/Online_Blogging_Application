<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg',
            'address' => 'required|string|max:255'
        ]);

        $imagePath = null;
        if ($request->hasFile('user_image')) {
            $imagePath = $request->file('user_image')->store('profiles', 'public');
        }

        $user = User::create([
            'first_name' => ucfirst(strtolower($request->first_name)),
            'last_name' => ucfirst(strtolower($request->last_name)),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'user_image' => $imagePath,
            'address' => $request->address,
            'role_id' => 2, // Default User Role
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! You can now log in to your account.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        // User Not Found or Wrong Password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid email or password']);
        }

        // Check User Approval Status
        if ($user->is_approved === 'Pending') {
            return back()->withErrors(['email' => 'Your account is still pending approval.']);
        }

        if ($user->is_approved === 'Rejected') {
            return back()->withErrors(['email' => 'Your account has been rejected.']);
        }

        // Check If User Is Active
        if ($user->is_active !== 'Active') {
            return back()->withErrors(['email' => 'Your account is inactive.']);
        }

        // Login and Redirect Based on Role
        $remember = $request->has('remember');
        Auth::login($user, $remember);
        $request->session()->regenerate();

        if ($user->role_id == 1) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'you are Logout successfully!');
    }
}
