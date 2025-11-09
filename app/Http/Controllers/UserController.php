<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display users list (AJAX)
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select('id', 'name', 'email', 'user_role', 'created_at')
                        ->orderBy('created_at', 'desc')
                        ->get();
            
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        }
        
        return view('users.index');
    }

    /**
     * Store a new user (AJAX)
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'user_role' => 'required|integer|in:1,2'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_role' => $request->user_role,
                'email_verified_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user
            ]);
        }
    }

    /**
     * Update user (AJAX)
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = User::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'user_role' => 'required|integer|in:1,2'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'user_role' => $request->user_role
            ]);

            if ($request->has('password') && !empty($request->password)) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user
            ]);
        }
    }

    /**
     * Delete user (AJAX)
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        }
    }

    /**
     * Get user details (AJAX)
     */
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = User::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        }
    }
}