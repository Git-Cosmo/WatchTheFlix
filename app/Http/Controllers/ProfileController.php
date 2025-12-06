<?php

namespace App\Http\Controllers;

use App\Services\RealDebridService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $user->loadCount(['watchlist', 'favorites', 'ratings', 'comments']);

        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully');
    }

    public function settings()
    {
        $user = Auth::user();

        return view('profile.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'real_debrid_token' => ['nullable', 'string'],
            'real_debrid_enabled' => ['boolean'],
            'parental_control_enabled' => ['boolean'],
            'parental_control_pin' => ['nullable', 'string', 'size:4'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Validate Real-Debrid token if provided
        if ($request->filled('real_debrid_token') && $request->boolean('real_debrid_enabled')) {
            $service = new RealDebridService($request->real_debrid_token);

            if (! $service->validateToken()) {
                return back()->withErrors([
                    'real_debrid_token' => 'Invalid Real-Debrid token.',
                ])->withInput();
            }
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->update([
            'real_debrid_token' => $validated['real_debrid_token'] ?? $user->real_debrid_token,
            'real_debrid_enabled' => $validated['real_debrid_enabled'] ?? false,
            'parental_control_enabled' => $validated['parental_control_enabled'] ?? false,
            'parental_control_pin' => $validated['parental_control_pin'] ?? $user->parental_control_pin,
        ]);

        return back()->with('success', 'Settings updated successfully');
    }
}
