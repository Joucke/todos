<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <x-breadcrumb :items="[
                    ['title' => 'my groups', 'url' => route('groups.index'), 'icon' => 'group'],
                    ['title' => $group->name, 'url' => route('groups.show', $group)],
                    ['title' => 'change group settings']
                ]" />
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Update your group settings and preferences
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-4 lg:gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form method="POST" action="{{ route('groups.update', $group) }}" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Name Field -->
                            <div>
                                <label for="name" class="form-label">Group Name</label>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="form-input @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                    value="{{ old('name', $group->name) }}"
                                    autocomplete="off"
                                    data-1p-ignore
                                    required
                                    autofocus
                                    placeholder="Enter a name for your group"
                                >
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Choose a descriptive name that helps identify the purpose of this group.</p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('groups.show', $group) }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-900 dark:text-gray-100 font-medium py-2 px-4 rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800">Cancel</a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                    <x-icon type="save" size="md" class="mr-2" />
                                    Save Group Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="mt-8 lg:mt-0">
                <div class="space-y-6">
                <!-- Danger Zone (NEW: using x-sidebar-box) -->
                @can('delete', $group)
                <x-sidebar-box type="danger" title="Danger Zone" icon="warning">
                    <p class="mb-4">
                        Delete this group and all its data. This action cannot be undone and will remove all task lists, tasks, and member relationships.
                    </p>
                    <button type="button"
                            onclick="document.getElementById('delete-group-modal').classList.remove('hidden')"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg text-xs transition-colors duration-200">
                        Delete Group
                    </button>
                </x-sidebar-box>
                @endcan

                <!-- Delete Confirmation Modal -->
                <div id="delete-group-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                        <div class="mt-3">
                            <!-- Warning Icon -->
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>

                            <!-- Modal Content -->
                            <div class="mt-5 text-center">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Delete Group</h3>
                                <div class="mt-2 px-7 py-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        Are you sure you want to delete <strong>{{ $group->name }}</strong>? This will permanently delete all tasks, lists, and member data. This action cannot be undone.
                                    </p>
                                </div>

                                <!-- Modal Actions -->
                                <div class="flex items-center justify-center space-x-4 px-4 py-3">
                                    <button onclick="document.getElementById('delete-group-modal').classList.add('hidden')"
                                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-lg transition-colors duration-200">
                                        Cancel
                                    </button>
                                    <form method="POST" action="{{ route('groups.destroy', $group) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            Delete Group
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
