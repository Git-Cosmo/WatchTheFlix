<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->loadCount(['watchlist', 'favorites', 'ratings', 'comments']);

        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->update($validated);

        return back()->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin() && User::role('admin')->count() === 1) {
            return back()->with('error', 'Cannot delete the last admin user');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }
}
