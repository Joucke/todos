<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['title' => 'my groups', 'url' => route('groups.index'), 'icon' => 'group'],
            ['title' => $group->name, 'url' => route('groups.show', $group)],
            ['title' => $taskList->name, 'url' => route('groups.show', ['group' => $taskList->group, 'list' => $taskList->id])],
            ['title' => 'change task list']
        ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-4 lg:gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Task List Settings</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Change the settings for {{ $taskList->name }}.</p>
                        </div>

                        <form method="POST" action="{{ route('task-lists.update', $taskList) }}" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Name Field -->
                            <div>
                                <label for="name" class="form-label">Task List Name</label>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="form-input @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                    value="{{ old('name', $taskList->name) }}"
                                    autocomplete="off"
                                    data-1p-ignore
                                    required
                                    autofocus
                                    placeholder="Enter a name for your task list"
                                >
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Choose a descriptive name that helps identify the purpose of this task list.</p>
                            </div>

                            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('groups.show', ['group' => $taskList->group, 'list' => $taskList->id]) }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-900 dark:text-gray-100 font-medium py-2 px-4 rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800">Cancel</a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                    <x-icon type="save" size="md" class="mr-2" />
                                    Save Task List Settings
                                </button>
                            </div>
                        </form>
                    </div>

                <!-- Sidebar -->
                <div class="mt-8 lg:mt-0">
                    <div class="space-y-6">

                        <!-- Group Context -->
                        <x-sidebar-box type="neutral" title="Part of {{ $group->name }}" icon="group">
                            <div class="space-y-2 text-sm">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Task Lists in this group:</p>
                                @foreach($group->taskLists->sortBy('sort_order') as $list)
                                    <div class="flex items-center justify-between py-1">
                                        <span class="@if($list->id === $taskList->id) font-medium text-indigo-600 dark:text-indigo-400 @else text-gray-700 dark:text-gray-300 @endif">
                                            {{ $list->name }}
                                        </span>
                                        @if($list->id === $taskList->id)
                                            <span class="text-xs bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 px-2 py-1 rounded-full">
                                                editing
                                            </span>
                                        @endif
                                    </div>
                                @endforeach

                                <div class="border-t border-gray-200 dark:border-gray-600 my-3"></div>

                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Tasks in this task list:</p>
                                @if($taskList->tasks->count() > 0)
                                    @foreach($taskList->tasks->take(5) as $task)
                                        <div class="py-1">
                                            <span class="text-gray-700 dark:text-gray-300">{{ $task->name }}</span>
                                        </div>
                                    @endforeach
                                    @if($taskList->tasks->count() > 5)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            and {{ $taskList->tasks->count() - 5 }} more...
                                        </div>
                                    @endif
                                @else
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        No tasks yet
                                    </div>
                                @endif
                            </div>
                        </x-sidebar-box>

                        <!-- Timeline -->
                        <x-sidebar-box type="neutral" title="Timeline" icon="clock">
                            <div class="space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span>Created:</span>
                                    <span>{{ $taskList->created_at->format('M j, Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Last Updated:</span>
                                    <span>{{ $taskList->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </x-sidebar-box>

                        <!-- Danger Zone -->
                        @can('delete', $taskList)
                            <x-sidebar-box type="danger" title="Danger Zone" icon="warning">
                                <p class="mb-4">
                                    Remove this task list and all its tasks. This action cannot be undone.
                                </p>
                                <button type="button"
                                        onclick="document.getElementById('delete-task-list-modal').classList.remove('hidden')"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg text-xs transition-colors duration-200">
                                    Remove Task List
                                </button>
                            </x-sidebar-box>

                            <!-- Delete Confirmation Modal -->
                            <div id="delete-task-list-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                                    <div class="mt-3 text-center">
                                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20">
                                            <x-icon type="warning" size="lg" class="text-red-600 dark:text-red-400" />
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-2">Remove Task List</h3>
                                        <div class="mt-2 px-7 py-3">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Are you sure you want to remove "{{ $taskList->name }}" and all its tasks? This action cannot be undone.
                                            </p>
                                        </div>
                                        <div class="flex justify-center space-x-3 mt-4">
                                            <button type="button"
                                                    onclick="document.getElementById('delete-task-list-modal').classList.add('hidden')"
                                                    class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-900 dark:text-gray-100 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                                Cancel
                                            </button>
                                            <form method="POST" action="{{ route('task-lists.destroy', $taskList) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                                    Remove Task List
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
