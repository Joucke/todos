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
                <x-icon type="group" size="md" class="mr-2" />
                {{ $group->members->count() }} {{ Str::plural('member', $group->members->count()) }}
            </div>
        @else
            <!-- Member group: show owner -->
            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <x-icon type="person" size="md" class="mr-2" />
                Owner: {{ $group->owner->name }}
            </div>
        @endif

        <!-- Task lists count -->
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
            <x-icon type="list" size="md" class="mr-2" />
            {{ $group->taskLists->count() }} {{ Str::plural('list', $group->taskLists->count()) }}
        </div>
    </div>

    <div class="flex justify-end">
        @if($isOwned)
            <x-icon-button href="{{ route('groups.edit', $group) }}" title="Change group settings">
                <x-icon type="edit" size="md" />
            </x-icon-button>
        @else
            <x-icon-button
                type="button"
                variant="red"
                title="Leave group"
                onclick="openLeaveGroupModal('{{ $group->id }}')">
                <x-icon type="leave" size="md" />
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
                    <x-icon type="warning" size="xl" class="text-red-600 dark:text-red-400" />
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
