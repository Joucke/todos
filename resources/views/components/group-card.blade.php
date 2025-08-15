@props([
    'group',
    'isOwned' => false,
])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
    <div class="flex items-start justify-between mb-4">
        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            <a href="{{ route('groups.show', $group) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200">
                {{ $group->name }}
            </a>
        </h4>
        @if($isOwned)
            <x-badge variant="blue">Owner</x-badge>
        @else
            <x-badge variant="green">Member</x-badge>
        @endif
    </div>

    <div class="space-y-3 mb-4">
        @if($isOwned)
            <!-- Owned group: show members count -->
            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
                {{ $group->members->count() }} {{ Str::plural('member', $group->members->count()) }}
            </div>
        @else
            <!-- Member group: show owner -->
            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
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
        @if($isOwned)
            <x-icon-button href="{{ route('groups.edit', $group) }}" title="Change group settings">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </x-icon-button>
        @else
            <x-icon-button
                type="button"
                variant="red"
                title="Leave group"
                onclick="openLeaveGroupModal('{{ $group->id }}')">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </x-icon-button>
        @endif
    </div>
</div>

@unless($isOwned)
    <!-- Leave Group Confirmation Modal -->
    <div id="leave-group-modal-{{ $group->id }}" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" onclick="closeLeaveGroupModal('{{ $group->id }}', event)">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800" onclick="event.stopPropagation()">
            <div class="mt-3">
                <!-- Warning Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>

                <!-- Modal Content -->
                <div class="mt-5 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Leave Group</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Are you sure you want to leave <strong>{{ $group->name }}</strong>? You will lose access to all lists and tasks in this group.
                        </p>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-center space-x-4 px-4 py-3">
                        <button onclick="closeLeaveGroupModal('{{ $group->id }}')"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                        <form method="POST" action="{{ route('groups.leave', $group) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                Leave Group
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openLeaveGroupModal(groupId) {
            const modal = document.getElementById(`leave-group-modal-${groupId}`);
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Focus first button in modal
            const firstButton = modal.querySelector('button');
            if (firstButton) firstButton.focus();
        }

        function closeLeaveGroupModal(groupId, event) {
            // Only close if clicking backdrop or called directly
            if (event && event.target !== event.currentTarget) return;

            const modal = document.getElementById(`leave-group-modal-${groupId}`);
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Handle escape key for all modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const openModals = document.querySelectorAll('[id^="leave-group-modal-"]:not(.hidden)');
                openModals.forEach(modal => {
                    const groupId = modal.id.replace('leave-group-modal-', '');
                    closeLeaveGroupModal(groupId);
                });
            }
        });
    </script>
@endunless
