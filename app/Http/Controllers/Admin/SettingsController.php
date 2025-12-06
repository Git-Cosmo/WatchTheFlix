<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:500'],
            'maintenance_mode' => ['nullable', 'boolean'],
            'allow_registration' => ['nullable', 'boolean'],
            'require_email_verification' => ['nullable', 'boolean'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, is_bool($value) ? 'boolean' : 'string');
        }

        activity()
            ->causedBy(auth()->user())
            ->log('Global settings updated');

        return back()->with('success', 'Settings updated successfully');
    }
}
