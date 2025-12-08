<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-dark-700 transition-colors">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
        </svg>
        <span class="text-sm font-medium">{{ strtoupper(app()->getLocale()) }}</span>
        <svg class="h-4 w-4" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div x-show="open" @click.away="open = false" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-48 bg-dark-800 border border-dark-700 rounded-lg shadow-lg py-1 z-50">
        
        <a href="{{ route('language.switch', 'en') }}" 
           class="flex items-center gap-3 px-4 py-2 hover:bg-dark-700 transition-colors {{ app()->getLocale() === 'en' ? 'text-accent-500' : '' }}">
            <span class="text-lg">🇬🇧</span>
            <span>English</span>
        </a>

        <a href="{{ route('language.switch', 'es') }}" 
           class="flex items-center gap-3 px-4 py-2 hover:bg-dark-700 transition-colors {{ app()->getLocale() === 'es' ? 'text-accent-500' : '' }}">
            <span class="text-lg">🇪🇸</span>
            <span>Español</span>
        </a>

        <a href="{{ route('language.switch', 'fr') }}" 
           class="flex items-center gap-3 px-4 py-2 hover:bg-dark-700 transition-colors {{ app()->getLocale() === 'fr' ? 'text-accent-500' : '' }}">
            <span class="text-lg">🇫🇷</span>
            <span>Français</span>
        </a>

        <a href="{{ route('language.switch', 'de') }}" 
           class="flex items-center gap-3 px-4 py-2 hover:bg-dark-700 transition-colors {{ app()->getLocale() === 'de' ? 'text-accent-500' : '' }}">
            <span class="text-lg">🇩🇪</span>
            <span>Deutsch</span>
        </a>

        <a href="{{ route('language.switch', 'it') }}" 
           class="flex items-center gap-3 px-4 py-2 hover:bg-dark-700 transition-colors {{ app()->getLocale() === 'it' ? 'text-accent-500' : '' }}">
            <span class="text-lg">🇮🇹</span>
            <span>Italiano</span>
        </a>
    </div>
</div>
