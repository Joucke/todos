<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    my groups
                </h2>
                @if($ownedGroups->count() > 0 || $groups->count() > 0)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        @if($ownedGroups->count() > 0)
                            {{ $ownedGroups->count() }} {{ Str::plural('owned', $ownedGroups->count()) }}
                        @endif
                        @if($ownedGroups->count() > 0 && $groups->count() > 0)
                            •
                        @endif
                        @if($groups->count() > 0)
                            {{ $groups->count() }} {{ Str::plural('member', $groups->count()) }}
                        @endif
                    </p>
                @endif
            </div>
            <x-icon-button href="{{ route('groups.create') }}" title="add a group">
                <x-icon type="add" />
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
                    <x-icon type="group" size="3xl" class="mx-auto text-gray-400" />
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">No groups yet</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Get started by creating your first group to organize your tasks.</p>
                    <div class="mt-6">
                        <a href="{{ route('groups.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <x-icon type="add" size="lg" class="mr-2" />
                            Add Your First Group
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
