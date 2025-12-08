@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <x-breadcrumbs :items="[
        ['label' => 'Admin', 'url' => route('admin.dashboard')],
        ['label' => 'Activity Log']
    ]" />

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">User Activity Log</h1>
        <a href="{{ route('admin.activity-log.export') }}" class="btn btn-primary">
            Export CSV
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full rounded-md border-gray-300" placeholder="Search activities...">
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-2">Activity Type</label>
                <select name="activity_type" class="w-full rounded-md border-gray-300">
                    <option value="">All Types</option>
                    @foreach($activityTypes as $type)
                        <option value="{{ $type }}" {{ request('activity_type') == $type ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $type)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full rounded-md border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full rounded-md border-gray-300">
            </div>

            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('admin.activity-log.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <!-- Activity Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($activities as $activity)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium">{{ $activity->causer?->name ?? 'System' }}</div>
                            @if($activity->causer)
                                <div class="text-sm text-gray-500">{{ $activity->causer->email }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $activity->log_name }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $activity->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $activity->properties['ip_address'] ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No activities found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $activities->withQueryString()->links() }}</div>
</div>
@endsection
