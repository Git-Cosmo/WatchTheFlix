@extends('layouts.app')

@section('title', 'Bouquet Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <x-breadcrumbs :items="[
        ['label' => 'Bouquets', 'url' => route('admin.bouquets.index')]
    ]" />

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ __('Channel Bouquets') }}</h1>
        <a href="{{ route('admin.bouquets.create') }}" class="btn-primary">
            {{ __('Create Bouquet') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Bouquets Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($bouquets as $bouquet)
            <div class="bg-dark-800 rounded-lg border border-dark-700 p-6 hover:border-accent-500 transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-semibold">{{ $bouquet->name }}</h3>
                        @if($bouquet->requires_subscription)
                            <span class="inline-block mt-2 px-2 py-1 bg-purple-500/20 text-purple-400 rounded text-xs">
                                {{ __('Requires Subscription') }}
                            </span>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        @if($bouquet->is_active)
                            <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">
                                {{ __('Active') }}
                            </span>
                        @else
                            <span class="px-2 py-1 bg-gray-500/20 text-gray-400 rounded text-xs">
                                {{ __('Inactive') }}
                            </span>
                        @endif
                    </div>
                </div>

                <p class="text-dark-400 text-sm mb-4">{{ $bouquet->description }}</p>

                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-2xl font-bold {{ $bouquet->price == 0 ? 'text-green-500' : 'text-accent-500' }}">
                            {{ $bouquet->formatted_price }}
                        </p>
                        <p class="text-xs text-dark-400">
                            {{ $bouquet->duration_days > 0 ? $bouquet->duration_days . ' days' : 'Lifetime' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-semibold">{{ $bouquet->channels_count }}</p>
                        <p class="text-xs text-dark-400">{{ __('Channels') }}</p>
                    </div>
                </div>

                <div class="flex gap-2 pt-4 border-t border-dark-700">
                    <a href="{{ route('admin.bouquets.edit', $bouquet) }}" 
                       class="flex-1 text-center px-4 py-2 bg-accent-500 hover:bg-accent-600 rounded-lg text-white transition">
                        {{ __('Edit') }}
                    </a>
                    <form method="POST" action="{{ route('admin.bouquets.destroy', $bouquet) }}"
                          onsubmit="return confirm('{{ __('Are you sure?') }}');" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg transition">
                            {{ __('Delete') }}
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-dark-800 rounded-lg border border-dark-700 p-12 text-center">
                <p class="text-dark-400 text-lg">{{ __('No bouquets found.') }}</p>
                <p class="text-dark-500 mt-2">{{ __('Create your first channel package to get started.') }}</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
