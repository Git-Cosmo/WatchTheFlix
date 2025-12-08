@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Manage Users</h1>

    <div class="card">
        <table class="w-full">
            <thead>
                <tr class="border-b border-dark-700">
                    <th class="text-left p-4">Name</th>
                    <th class="text-left p-4">Email</th>
                    <th class="text-left p-4">Role</th>
                    <th class="text-left p-4">Real-Debrid</th>
                    <th class="text-left p-4">Joined</th>
                    <th class="text-right p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b border-dark-700">
                    <td class="p-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-accent-500 flex items-center justify-center">
                                <span class="text-sm font-medium">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <span class="font-medium">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="p-4">{{ $user->email }}</td>
                    <td class="p-4">
                        @foreach($user->roles as $role)
                        <span class="px-2 py-1 rounded text-xs {{ $role->name === 'admin' ? 'bg-accent-900 text-accent-300' : 'bg-dark-700 text-dark-300' }}">
                            {{ ucfirst($role->name) }}
                        </span>
                        @endforeach
                    </td>
                    <td class="p-4">
                        @if($user->real_debrid_enabled)
                        <span class="text-green-500">✓ Enabled</span>
                        @else
                        <span class="text-dark-500">Disabled</span>
                        @endif
                    </td>
                    <td class="p-4 text-sm text-dark-400">{{ $user->created_at->diffForHumans() }}</td>
                    <td class="p-4 text-right space-x-2">
                        <a href="{{ route('admin.users.show', $user) }}" class="text-accent-500 hover:text-accent-400">View</a>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-400" onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-dark-400">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
