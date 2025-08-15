@props([
    'group',
    'badge' => null,
    'showEdit' => false,
    'showLeave' => false,
])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
    <div class="flex items-start justify-between mb-4">
        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            <a href="{{ route('groups.show', $group) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200">
                {{ $group->name }}
            </a>
        </h4>
        @if($badge)
            <x-badge :variant="$badge['variant']">{{ $badge['text'] }}</x-badge>
        @endif
    </div>

    <div class="space-y-3 mb-4">
        @if($showEdit)
            <!-- Owned group: show members with avatars -->
            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m3 5.197z"/>
                    </svg>
                    {{ $group->members->count() }} {{ Str::plural('member', $group->members->count()) }}
                </div>
                <div class="flex -space-x-1">
                    @foreach($group->members->take(4) as $member)
                        <div class="h-6 w-6 bg-indigo-500 rounded-full flex items-center justify-center text-white text-xs font-medium border-2 border-white dark:border-gray-800" title="{{ $member->name }}">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </div>
                    @endforeach
                    @if($group->members->count() > 4)
                        <div class="h-6 w-6 bg-gray-400 rounded-full flex items-center justify-center text-white text-xs font-medium border-2 border-white dark:border-gray-800" title="{{ $group->members->count() - 4 }} more">
                            +{{ $group->members->count() - 4 }}
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Member group: show owner -->
            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Owner: {{ $group->owner->name }}
            </div>
        @endif

        <!-- Task lists count -->
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            {{ $group->taskLists->count() }} {{ Str::plural('list', $group->taskLists->count()) }}
        </div>
    </div>

    <div class="flex justify-end">
        @if($showEdit)
            <x-icon-button href="{{ route('groups.edit', $group) }}" title="Change group settings">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </x-icon-button>
        @endif

        @if($showLeave)
            <form method="POST" action="{{ route('groups.leave', $group) }}" class="flex">
                @csrf
                @method('DELETE')
                <x-icon-button type="submit" variant="red" title="Leave group"
                               onclick="return confirm('Are you sure you want to leave this group?')">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </x-icon-button>
            </form>
        @endif
    </div>
</div>
