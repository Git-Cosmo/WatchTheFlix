<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function index()
    {
        $invites = Invite::with(['creator', 'user'])->latest()->paginate(20);

        return view('admin.invites.index', compact('invites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        $invite = Invite::create([
            'code' => Invite::generateCode(),
            'email' => $validated['email'],
            'created_by' => Auth::id(),
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($invite)
            ->log('Invite created');

        return back()->with('success', 'Invite created successfully. Code: ' . $invite->code);
    }

    public function destroy(Invite $invite)
    {
        $invite->delete();

        return back()->with('success', 'Invite deleted successfully');
    }
}
