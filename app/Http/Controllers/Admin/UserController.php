<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withoutTrashed()->get();

        $stats = [
            'total'       => User::withTrashed()->count(),
            'active'      => User::withoutTrashed()->count(),
            'admin'       => User::withoutTrashed()->where('role', 'admin')->count(),
            'manager'     => User::withoutTrashed()->where('role', 'manager')->count(),
            'sales_staff' => User::withoutTrashed()->where('role', 'sales_staff')->count(),
        ];

        $archivedCount = User::onlyTrashed()->count();

        return view('admin.users.index', compact('users', 'stats', 'archivedCount'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,manager,sales_staff',
            'phone'    => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'phone'    => $request->phone,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'user'    => $user,
        ]);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,manager,sales_staff',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
            'phone' => $request->phone,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'user'    => $user->fresh(),
        ]);
    }

    public function archived()
    {
        $users = User::onlyTrashed()->orderByDesc('deleted_at')->get();

        return view('admin.users.archived', compact('users'));
    }

    /**
     * Archive (soft delete) a user - FIXED VERSION
     */
    public function archive($id)
    {
        // Use find() instead of findOrFail to prevent automatic 404
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'User not found.'
            ], 404);
        }

        // Prevent archiving yourself
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'error' => 'You cannot archive your own account.'
            ], 403);
        }

        if ($user->trashed()) {
            return response()->json([
                'success' => false,
                'error' => 'User is already archived.'
            ], 400);
        }

        $user->delete();   // Soft delete

        return response()->json([
            'success' => true,
            'message' => 'User archived successfully.'
        ]);
    }

    /**
     * Restore an archived user - FIXED VERSION
     */
    public function restore($id)
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'User not found.'
            ], 404);
        }

        if (!$user->trashed()) {
            return response()->json([
                'success' => false,
                'error' => 'User is not archived.'
            ], 400);
        }

        $user->restore();

        return response()->json([
            'success' => true,
            'message' => 'User restored successfully.'
        ]);
    }
}