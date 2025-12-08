@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <x-breadcrumbs :items="[
        ['label' => 'Admin', 'url' => route('admin.dashboard')],
        ['label' => 'Transcoding']
    ]" />

    <div class="mb-6">
        <h1 class="text-3xl font-bold mb-2">Transcoding Management</h1>
        <p class="text-gray-600">Monitor and manage media transcoding jobs</p>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-500 mb-1">Pending</div>
            <div class="text-3xl font-bold">{{ $stats['pending'] }}</div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-500 mb-1">Processing</div>
            <div class="text-3xl font-bold text-blue-600">{{ $stats['processing'] }}</div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-500 mb-1">Completed</div>
            <div class="text-3xl font-bold text-green-600">{{ $stats['completed'] }}</div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-500 mb-1">Failed</div>
            <div class="text-3xl font-bold text-red-600">{{ $stats['failed'] }}</div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-sm font-medium text-gray-500 mb-1">Media Transcoded</div>
            <div class="text-3xl font-bold">{{ $stats['total_media_transcoded'] }}</div>
        </div>
    </div>

    <!-- Jobs Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Media</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quality</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jobs as $job)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium">{{ $job->media->title }}</div>
                            <div class="text-xs text-gray-500">ID: {{ $job->media->id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $job->quality }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($job->status === 'completed') bg-green-100 text-green-800
                                @elseif($job->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($job->status === 'failed') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $job->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $job->progress }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">{{ $job->progress }}%</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $job->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($job->isPending())
                                <button onclick="processJob({{ $job->id }})" class="text-blue-600 hover:text-blue-800">Process</button>
                            @endif
                            <form method="POST" action="{{ route('admin.transcoding.destroy', $job) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 ml-3">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">No transcoding jobs found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $jobs->links() }}</div>
</div>

<script>
function processJob(jobId) {
    if (!confirm('Start processing this transcode job?')) return;
    
    fetch(`/admin/transcoding/${jobId}/process`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message, 'error');
        }
    });
}
</script>
@endsection
