<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Groups') }}
            </h2>
            <x-icon-button href="{{ route('groups.create') }}" title="Add new group">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </x-icon-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Messages -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Owned Groups -->
            @if($ownedGroups->isNotEmpty())
                <div class="mb-8">
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($ownedGroups as $group)
                            <x-group-card
                                :group="$group"
                                :isOwned="true" />
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Separator -->
            @if($ownedGroups->isNotEmpty() && $groups->isNotEmpty())
                <div class="relative mb-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-4 bg-gray-50 dark:bg-gray-900 text-sm text-gray-500 dark:text-gray-400">More Groups</span>
                    </div>
                </div>
            @endif

            <!-- Member Groups -->
            @if($groups->isNotEmpty())
                <div>
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($groups as $group)
                            <x-group-card
                                :group="$group"
                                :isOwned="false" />
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Empty State -->
            @if($groups->isEmpty() && $ownedGroups->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">No groups yet</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Get started by creating your first group to organize your tasks.</p>
                    <div class="mt-6">
                        <a href="{{ route('groups.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create Your First Group
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
