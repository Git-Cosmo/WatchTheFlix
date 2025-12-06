<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        $inviteCode = $request->query('invite');
        $invite = null;

        if ($inviteCode) {
            $invite = Invite::where('code', $inviteCode)->first();
            
            if (!$invite || !$invite->isValid()) {
                return redirect()->route('login')
                    ->with('error', 'Invalid or expired invite code.');
            }
        } else {
            // Check if invite is required
            $userCount = User::count();
            if ($userCount > 0) {
                return redirect()->route('login')
                    ->with('error', 'Registration requires an invite code.');
            }
        }

        return view('auth.register', ['invite' => $invite]);
    }

    public function register(Request $request)
    {
        $userCount = User::count();
        
        // Validate invite code if not first user
        if ($userCount > 0) {
            $request->validate([
                'invite_code' => ['required', 'string'],
            ]);

            $invite = Invite::where('code', $request->invite_code)->first();

            if (!$invite || !$invite->isValid()) {
                return back()->withErrors([
                    'invite_code' => 'Invalid or expired invite code.',
                ])->withInput();
            }

            // Verify email matches invite
            if ($invite->email !== $request->email) {
                return back()->withErrors([
                    'email' => 'Email does not match the invite.',
                ])->withInput();
            }
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // First user becomes admin
        if ($userCount === 0) {
            if (!Role::where('name', 'admin')->exists()) {
                Role::create(['name' => 'admin']);
            }
            $user->assignRole('admin');
            
            activity()
                ->causedBy($user)
                ->log('First user registered as admin');
        } else {
            // Mark invite as used
            $invite->update([
                'used_by' => $user->id,
                'used_at' => now(),
            ]);
            
            activity()
                ->causedBy($user)
                ->log('User registered with invite code');
        }

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Welcome to WatchTheFlix!');
    }
}
