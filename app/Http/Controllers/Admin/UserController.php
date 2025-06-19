<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $userType = $request->input('user_type');
        $status = $request->input('status');

        $users = User::query()
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('country', 'like', "%{$search}%");
            })
            ->when($userType, function($query) use ($userType) {
                $query->where('user_type', $userType);
            })
            ->when($status, function($query) use ($status) {
                if ($status === 'verified') {
                    $query->where('is_verified', 1);
                } elseif ($status === 'unverified') {
                    $query->where('is_verified', 0);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users', 'search', 'userType', 'status'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        // Get user's reviews count (if they have any)
        $reviewsCount = \App\Models\Rate::where('user_id', $id)->count();

        return view('admin.users.show', compact('user', 'reviewsCount'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'country' => 'nullable|string|max:255',
            'user_type' => 'required|in:regular user,business owner,admin',
            'is_verified' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'password' => 'nullable|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validatedData['profile_picture'] = $path;
        }

        // Handle password update
        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        } else {
            unset($validatedData['password']);
        }

        // Handle boolean fields
        $validatedData['is_verified'] = $request->has('is_verified') ? 1 : 0;
        $validatedData['two_factor_enabled'] = $request->has('two_factor_enabled') ? 1 : 0;

        $user->update($validatedData);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'User updated successfully.']);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Delete profile picture if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Delete user's reviews
        \App\Models\Rate::where('user_id', $id)->delete();

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function verify($id)
    {
        $user = User::findOrFail($id);
        $user->is_verified = 1;
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User verified successfully.');
    }

    public function unverify($id)
    {
        $user = User::findOrFail($id);
        $user->is_verified = 0;
        $user->email_verified_at = null;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User unverified successfully.');
    }
}
