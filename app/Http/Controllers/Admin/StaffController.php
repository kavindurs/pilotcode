<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $staff = Admin::query()
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone_number', 'like', "%{$search}%");
            })
            ->when($role, function($query) use ($role) {
                $query->where('role', $role);
            })
            ->when($status, function($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.staff.index', compact('staff', 'search', 'role', 'status'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:255',
            'role' => 'required|in:editor,admin,accountant,worker,super_admin',
            'status' => 'required|in:active,inactive,suspended'
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        Admin::create($validatedData);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member created successfully.');
    }

    public function show($id)
    {
        $staffMember = Admin::findOrFail($id);
        return view('admin.staff.show', compact('staffMember'));
    }

    public function edit($id)
    {
        $staffMember = Admin::findOrFail($id);
        return view('admin.staff.edit', compact('staffMember'));
    }

    public function update(Request $request, $id)
    {
        $staffMember = Admin::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:255',
            'role' => 'required|in:editor,admin,accountant,worker,super_admin',
            'status' => 'required|in:active,inactive,suspended'
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $staffMember->update($validatedData);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy($id)
    {
        $staffMember = Admin::findOrFail($id);

        // Prevent deleting the currently logged-in admin
        if ($staffMember->id === auth('admin')->id()) {
            return redirect()->route('admin.staff.index')->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting super admin accounts (optional safety measure)
        if ($staffMember->role === 'super_admin') {
            return redirect()->route('admin.staff.index')->with('error', 'Super admin accounts cannot be deleted.');
        }

        $staffMember->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member deleted successfully.');
    }

    public function activate($id)
    {
        $staffMember = Admin::findOrFail($id);
        $staffMember->update(['status' => 'active']);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member activated successfully.');
    }

    public function deactivate($id)
    {
        $staffMember = Admin::findOrFail($id);

        // Prevent deactivating the currently logged-in admin
        if ($staffMember->id === auth('admin')->id()) {
            return redirect()->route('admin.staff.index')->with('error', 'You cannot deactivate your own account.');
        }

        $staffMember->update(['status' => 'inactive']);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member deactivated successfully.');
    }

    public function suspend($id)
    {
        $staffMember = Admin::findOrFail($id);

        // Prevent suspending the currently logged-in admin
        if ($staffMember->id === auth('admin')->id()) {
            return redirect()->route('admin.staff.index')->with('error', 'You cannot suspend your own account.');
        }

        $staffMember->update(['status' => 'suspended']);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member suspended successfully.');
    }
}
