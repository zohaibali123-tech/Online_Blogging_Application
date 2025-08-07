<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('role_id', '!=', 1); // Exclude Admins

        if ($request->has('search') && $request->search !== null) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'LIKE', "%$searchTerm%")
                ->orWhere('last_name', 'LIKE', "%$searchTerm%")
                ->orWhere('address', 'LIKE', "%$searchTerm%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            return view('admin.user.partials.user_table', compact('users'))->render();
        }

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = DB::table('roles')->get();
        return view('admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'nullable|string|max:255',
            'is_approved' => 'required|in:Pending,Approved,Rejected',
            'is_active' => 'required|in:Active,InActive',
        ]);

        $imagePath = null;
        if ($request->hasFile('user_image')) {
            $imagePath = $request->file('user_image')->store('profiles', 'public');
        }

        // Create User
        User::create([
            'role_id' => $request->role_id,
            'first_name' => ucfirst(strtolower($request->first_name)),
            'last_name' => ucfirst(strtolower($request->last_name)),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'user_image' => $imagePath,
            'address' => $request->address,
            'is_approved' => $request->is_approved,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User created successfully! ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = DB::table('roles')->get();
        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'nullable|date',
            'role_id' => 'required|exists:roles,id',
            'address' => 'nullable|string',
            'is_approved' => 'required|in:Approved,Pending,Rejected',
            'is_active' => 'required|in:Active,InActive',
            'user_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($request->hasFile('user_image')) {
            $imagePath = $request->file('user_image')->store('profiles', 'public');
            $validated['user_image'] = $imagePath;
        }

        $user->update($validated);

        return redirect()->route('admin.user.index')->with('success', 'User updated successfully! ');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // Toggle status
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $newStatus = $user->is_active === 'Active' ? 'InActive' : 'Active';
        $user->is_active = $newStatus;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => "User status updated to {$newStatus}.",
            'new_status' => $newStatus
        ]);
    }

    // Approved Rejected Pending
    public function approve(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $status = $request->input('status'); // Expected 'Approved' or 'Rejected'

        if (!in_array($status, ['Approved', 'Pending', 'Rejected'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid approval status.'
            ], 422);
        }

        $user->is_approved = $status;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => "User approval status updated to {$status}.",
            'new_status' => $status
        ]);
    }

    // User Profile
    public function showProfile($id)
    {
        if (auth()->user()->role_id != 1) {
            abort(403, 'Unauthorized access. Admins only.');
        }
        
        $user = User::findOrFail($id);
        $roleType = DB::table('roles')->where('id', $user->role_id)->value('role_type');
        return view('admin.user.profile', compact('user', 'roleType'));
    }

    // Approved
    public function approved(Request $request) {
        $users = User::where('role_id', '!=', 1)
                     ->where('is_approved', 'Approved')
                     ->paginate(10);
    
        if ($request->ajax()) {
            return view('admin.user.partials.user_table', compact('users'))->render();
        }
    
        return view('admin.user.filtered', [
            'users' => $users,
            'title' => 'Approved Users',
        ]);
    }
    
    // Pending
    public function pending(Request $request) {
        $users = User::where('role_id', '!=', 1)
                     ->where('is_approved', 'Pending')
                     ->paginate(10);
    
        if ($request->ajax()) {
            return view('admin.user.partials.user_table', compact('users'))->render();
        }
    
        return view('admin.user.filtered', [
            'users' => $users,
            'title' => 'Pending Users',
        ]);
    }
    
    // Rejected
    public function rejected(Request $request) {
        $users = User::where('role_id', '!=', 1)
                     ->where('is_approved', 'Rejected')
                     ->paginate(10);
    
        if ($request->ajax()) {
            return view('admin.user.partials.user_table', compact('users'))->render();
        }
    
        return view('admin.user.filtered', [
            'users' => $users,
            'title' => 'Rejected Users',
        ]);
    }
}
