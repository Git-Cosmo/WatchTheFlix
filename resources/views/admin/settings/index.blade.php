@extends('layouts.app')

@section('title', 'Global Settings')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Global Settings</h1>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Site Information -->
            <div class="card p-6">
                <h2 class="text-xl font-semibold mb-4">Site Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="site_name" class="block text-sm font-medium text-dark-300 mb-2">
                            Site Name
                        </label>
                        <input type="text" name="site_name" id="site_name" 
                               value="{{ old('site_name', $settings['site_name'] ?? 'WatchTheFlix') }}"
                               class="input-field w-full">
                    </div>

                    <div>
                        <label for="site_description" class="block text-sm font-medium text-dark-300 mb-2">
                            Site Description
                        </label>
                        <textarea name="site_description" id="site_description" rows="3"
                                  class="input-field w-full">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Access Control -->
            <div class="card p-6">
                <h2 class="text-xl font-semibold mb-4">Access Control</h2>
                
                <div class="space-y-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="maintenance_mode" value="1" 
                               {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}
                               class="rounded border-dark-600 text-accent-500 focus:ring-accent-500">
                        <span class="ml-2">Maintenance Mode</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="allow_registration" value="1" 
                               {{ ($settings['allow_registration'] ?? true) ? 'checked' : '' }}
                               class="rounded border-dark-600 text-accent-500 focus:ring-accent-500">
                        <span class="ml-2">Allow Registration (with invites)</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="require_email_verification" value="1" 
                               {{ ($settings['require_email_verification'] ?? false) ? 'checked' : '' }}
                               class="rounded border-dark-600 text-accent-500 focus:ring-accent-500">
                        <span class="ml-2">Require Email Verification</span>
                    </label>
                </div>
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="btn-primary">Save Settings</button>
                <a href="{{ route('admin.dashboard') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
