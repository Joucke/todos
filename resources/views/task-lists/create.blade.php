<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['title' => 'my groups', 'url' => route('groups.index'), 'icon' => 'group'],
            ['title' => $group->name, 'url' => route('groups.show', $group)],
            ['title' => 'add a task list']
        ]" />
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-4 lg:gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Task List Details</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Add a task list to organize your work in {{ $group->name }}.</p>
                        </div>

                        <form method="POST" action="{{ route('groups.task-lists.store', $group) }}" class="space-y-6">
                            @csrf

                            <div>
                                <label for="name" class="form-label">Task List Name</label>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="form-input @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                    value="{{ old('name') }}"
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
                                <a href="{{ route('groups.show', $group) }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-900 dark:text-gray-100 font-medium py-2 px-4 rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800">Cancel</a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                    <x-icon type="add" size="md" class="mr-2" />
                                    Add Task List
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="mt-8 lg:mt-0">
                    <div class="space-y-6">
                        <!-- Group Info -->
                        <x-sidebar-box type="neutral" title="Adding to {{ $group->name }}" icon="group">
                            <p class="mb-4">This task list will be visible to all group members and can be managed by group administrators.</p>

                            @if($group->taskLists->isNotEmpty())
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Existing Lists:</h4>
                                    <ul class="space-y-1">
                                        @foreach($group->taskLists as $taskList)
                                            <li class="text-sm text-gray-600 dark:text-gray-400">• {{ $taskList->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 italic">This will be the first task list in this group.</p>
                                </div>
                            @endif
                        </x-sidebar-box>

                        <!-- Examples -->
                        <x-sidebar-box type="info" title="Example Task Lists" icon="list">
                            <ul class="list-disc list-outside ml-4 space-y-1 max-w-none">
                                <li>"Weekly Cleaning" - Household chores</li>
                                <li>"Grocery Shopping" - Items to buy</li>
                                <li>"Home Repairs" - Fix and maintenance tasks</li>
                                <li>"Garden Projects" - Outdoor and plant care</li>
                            </ul>
                        </x-sidebar-box>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
