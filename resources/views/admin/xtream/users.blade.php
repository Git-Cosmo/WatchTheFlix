@extends('layouts.app')

@section('title', 'Xtream API Users')

@section('content')
<div class="container mx-auto px-4 py-8">
    <x-xtream-hold-notice />
    
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold">API Users Management</h1>
        <a href="{{ route('admin.xtream.index') }}" class="btn-secondary">Back to Dashboard</a>
    </div>

    <div class="card p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-700">
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">User</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Email</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">API Token</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Created</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-b border-dark-800">
                        <td class="py-3 px-4 font-medium">{{ $user->name }}</td>
                        <td class="py-3 px-4 text-sm text-dark-400">{{ $user->email }}</td>
                        <td class="py-3 px-4">
                            @if($user->tokens->isNotEmpty())
                                <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded">Active</span>
                            @else
                                <span class="px-2 py-1 bg-dark-700 text-dark-400 text-xs rounded">No Token</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm text-dark-400">
                            {{ $user->created_at->diffForHumans() }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2">
                                @if($user->tokens->isEmpty())
                                    <form method="POST" action="{{ route('admin.xtream.generate-token', $user) }}">
                                        @csrf
                                        <button type="submit" class="text-accent-400 hover:text-accent-300 text-sm">
                                            Generate Token
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.xtream.revoke-token', $user) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-sm" onclick="return confirm('Revoke API token?')">
                                            Revoke Token
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.xtream.export-m3u', $user) }}" class="text-blue-400 hover:text-blue-300 text-sm ml-3">
                                        Export M3U
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
