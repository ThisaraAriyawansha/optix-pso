<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('branch')->orderBy('name')->paginate(20);
        return view('settings.users.index', compact('users'));
    }

    public function create()
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        return view('settings.users.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:100|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,cashier,technician',
            'branch_id' => 'required|exists:branches,id',
            'is_active' => 'boolean',
        ]);
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->route('settings.users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        return view('settings.users.create', compact('user', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,cashier,technician',
            'branch_id' => 'required|exists:branches,id',
            'is_active' => 'boolean',
        ]);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        return redirect()->route('settings.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $user->update(['is_active' => false]);
        return redirect()->route('settings.users.index')->with('success', 'User deactivated.');
    }
}
