@extends('layouts.admin')

@section('title', 'Subscription Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <x-breadcrumbs :items="[
        ['label' => 'Subscriptions', 'url' => route('admin.subscriptions.index')]
    ]" />

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ __('Subscription Plans') }}</h1>
        <a href="{{ route('admin.subscriptions.create') }}" class="btn-primary">
            {{ __('Create New Plan') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-dark-800 rounded-lg p-6 border border-dark-700">
            <p class="text-dark-400 text-sm">{{ __('Active Subscriptions') }}</p>
            <p class="text-3xl font-bold mt-2">{{ number_format($activeSubscriptions) }}</p>
        </div>
        <div class="bg-dark-800 rounded-lg p-6 border border-dark-700">
            <p class="text-dark-400 text-sm">{{ __('Expired') }}</p>
            <p class="text-3xl font-bold mt-2">{{ number_format($expiredSubscriptions) }}</p>
        </div>
        <div class="bg-dark-800 rounded-lg p-6 border border-dark-700">
            <p class="text-dark-400 text-sm">{{ __('Total Plans') }}</p>
            <p class="text-3xl font-bold mt-2">{{ number_format($plans->count()) }}</p>
        </div>
    </div>

    <!-- Plans Table -->
    <div class="bg-dark-800 rounded-lg border border-dark-700 overflow-hidden">
        <table class="w-full">
            <thead class="bg-dark-700">
                <tr>
                    <th class="text-left px-6 py-4 font-semibold">{{ __('Plan Name') }}</th>
                    <th class="text-left px-6 py-4 font-semibold">{{ __('Price') }}</th>
                    <th class="text-left px-6 py-4 font-semibold">{{ __('Duration') }}</th>
                    <th class="text-left px-6 py-4 font-semibold">{{ __('Connections') }}</th>
                    <th class="text-left px-6 py-4 font-semibold">{{ __('Subscribers') }}</th>
                    <th class="text-left px-6 py-4 font-semibold">{{ __('Status') }}</th>
                    <th class="text-right px-6 py-4 font-semibold">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr class="border-t border-dark-700 hover:bg-dark-700/50">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold">{{ $plan->name }}</p>
                                <p class="text-sm text-dark-400">{{ $plan->slug }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold {{ $plan->price == 0 ? 'text-green-500' : 'text-accent-500' }}">
                                {{ $plan->formatted_price }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $plan->formatted_duration }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $plan->max_connections }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-sm">
                                {{ number_format($plan->subscriptions->count()) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($plan->is_active)
                                <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-sm">
                                    {{ __('Active') }}
                                </span>
                            @else
                                <span class="px-3 py-1 bg-gray-500/20 text-gray-400 rounded-full text-sm">
                                    {{ __('Inactive') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.subscriptions.edit', $plan) }}" 
                                   class="text-accent-500 hover:text-accent-400">
                                    {{ __('Edit') }}
                                </a>
                                @if($plan->subscriptions()->active()->count() == 0)
                                    <form method="POST" action="{{ route('admin.subscriptions.destroy', $plan) }}"
                                          onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-dark-400">
                            {{ __('No subscription plans found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Recent Subscriptions -->
    <div class="mt-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">{{ __('Recent Subscriptions') }}</h2>
            <a href="{{ route('admin.subscriptions.list') }}" class="text-accent-500 hover:text-accent-400">
                {{ __('View All') }} →
            </a>
        </div>

        <div class="bg-dark-800 rounded-lg border border-dark-700 p-6">
            <p class="text-dark-400">{{ __('Navigate to subscription list to view all user subscriptions.') }}</p>
        </div>
    </div>
</div>
@endsection
