@extends('layouts.app')

@section('title', 'Manage Invites')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Manage Invites</h1>

    <!-- Create Invite Form -->
    <div class="card p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Create New Invite</h2>
        <form method="POST" action="{{ route('admin.invites.store') }}" class="flex gap-4">
            @csrf
            <div class="flex-1">
                <input type="email" name="email" placeholder="Email address" required
                       class="input-field w-full @error('email') border-red-500 @enderror">
                @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex-1">
                <input type="datetime-local" name="expires_at"
                       class="input-field w-full">
            </div>
            <button type="submit" class="btn-primary whitespace-nowrap">Generate Invite</button>
        </form>
    </div>

    <!-- Invites List -->
    <div class="card">
        <table class="w-full">
            <thead>
                <tr class="border-b border-dark-700">
                    <th class="text-left p-4">Code</th>
                    <th class="text-left p-4">Email</th>
                    <th class="text-left p-4">Created By</th>
                    <th class="text-left p-4">Status</th>
                    <th class="text-left p-4">Used By</th>
                    <th class="text-right p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invites as $invite)
                <tr class="border-b border-dark-700">
                    <td class="p-4">
                        <code class="bg-dark-800 px-2 py-1 rounded text-sm">{{ $invite->code }}</code>
                    </td>
                    <td class="p-4">{{ $invite->email }}</td>
                    <td class="p-4">{{ $invite->creator->name }}</td>
                    <td class="p-4">
                        @if($invite->isUsed())
                        <span class="px-2 py-1 rounded text-xs bg-green-900 text-green-300">Used</span>
                        @elseif($invite->isExpired())
                        <span class="px-2 py-1 rounded text-xs bg-red-900 text-red-300">Expired</span>
                        @else
                        <span class="px-2 py-1 rounded text-xs bg-blue-900 text-blue-300">Active</span>
                        @endif
                    </td>
                    <td class="p-4">
                        @if($invite->user)
                        {{ $invite->user->name }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        @if(!$invite->isUsed())
                        <form method="POST" action="{{ route('admin.invites.destroy', $invite) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-400" onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-dark-400">No invites found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $invites->links() }}
    </div>
</div>
@endsection
