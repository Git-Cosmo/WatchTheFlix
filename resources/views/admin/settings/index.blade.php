@extends('layouts.admin')

@section('title', 'Global Settings')

@section('content')
<div class="max-w-7xl">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Global Settings</h1>
        <p class="text-dark-400 mt-2">Configure your platform settings and integrations</p>
    </div>

    <div class="max-w-4xl"

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Site Information -->
            <div class="card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-accent-500/10 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold">Site Information</h2>
                        <p class="text-dark-400 text-sm">Basic site configuration and branding</p>
                    </div>
                </div>
                
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

            <!-- API Integrations -->
            <div class="card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-blue-500/10 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold">API Integrations</h2>
                        <p class="text-dark-400 text-sm">Connect external services and APIs</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label for="tmdb_api_key" class="block text-sm font-medium text-dark-300 mb-2">
                            TMDB API Key
                            <span class="text-dark-500 text-xs">(Get your API key from <a href="https://www.themoviedb.org/settings/api" target="_blank" class="text-accent-400 hover:text-accent-300">TMDB</a>)</span>
                        </label>
                        <input type="text" name="tmdb_api_key" id="tmdb_api_key" 
                               value="{{ old('tmdb_api_key', $settings['tmdb_api_key'] ?? '') }}"
                               placeholder="Enter your TMDB API key"
                               class="input-field w-full font-mono text-sm">
                        <p class="text-xs text-dark-400 mt-1">
                            The Movie Database (TMDB) API is used for importing movie and TV show metadata including posters, descriptions, ratings, and streaming availability.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Access Control -->
            <div class="card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold">Access Control</h2>
                        <p class="text-dark-400 text-sm">Manage user access and registration</p>
                    </div>
                </div>
                
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

            <div class="flex items-center justify-between">
                <div class="flex gap-4">
                    <button type="submit" class="btn-primary inline-flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Settings
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn-secondary">Cancel</a>
                </div>
                <p class="text-xs text-dark-500">
                    Changes will take effect immediately
                </p>
            </div>
        </form>

        <!-- Additional Information -->
        <div class="mt-8 card p-6">
            <h3 class="font-semibold mb-3 flex items-center gap-2">
                <svg class="h-5 w-5 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                Configuration Tips:
            </h3>
            <div class="space-y-3 text-sm text-dark-300">
                <div class="flex items-start gap-2">
                    <svg class="h-5 w-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p><strong>TMDB API Key:</strong> Required for automatic content import and metadata enrichment. Get yours free at <a href="https://www.themoviedb.org/settings/api" target="_blank" class="text-accent-400 hover:text-accent-300 underline">themoviedb.org</a></p>
                </div>
                <div class="flex items-start gap-2">
                    <svg class="h-5 w-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p><strong>Maintenance Mode:</strong> Temporarily disable the site while you make updates. Admins can still access the site.</p>
                </div>
                <div class="flex items-start gap-2">
                    <svg class="h-5 w-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p><strong>Registration:</strong> Control whether new users can register. Invite system is always active for existing users.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
