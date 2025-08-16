<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <x-breadcrumb :items="[
                    ['title' => 'my groups', 'url' => route('groups.index'), 'icon' => 'group'],
                    ['title' => $group->name]
                ]" />
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ $group->members->count() }} {{ Str::plural('member', $group->members->count()) }} •
                    {{ $group->taskLists->count() }} {{ Str::plural('list', $group->taskLists->count()) }}
                </p>
            </div>
            <div class="flex space-x-3">
                @can('update', $group)
                    <x-icon-button href="{{ route('groups.task-lists.create', $group) }}" title="add a list">
                        <x-icon type="add" />
                    </x-icon-button>
                    <x-icon-button href="{{ route('groups.edit', $group) }}" title="change group settings">
                        <x-icon type="edit" />
                    </x-icon-button>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Messages -->
            @if (session('success'))
                <div class="mb-6">
                    <x-sidebar-box type="success">
                        {{ session('success') }}
                    </x-sidebar-box>
                </div>
            @endif

            <div class="lg:grid lg:grid-cols-4 lg:gap-8">
                <!-- Main Content - Task Lists -->
                <div class="lg:col-span-3">
                    @if($group->taskLists->isNotEmpty())
                        <div class="space-y-8">
                            @foreach($group->taskLists as $taskList)
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                    <div class="flex items-center justify-between mb-6">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                <a href="{{ route('task-lists.show', $taskList) }}" class="hover:text-blue-600 transition-colors duration-200">
                                                    {{ $taskList->name }}
                                                </a>
                                            </h3>
                                            @if($taskList->description)
                                                <p class="text-sm text-gray-600 mt-1">{{ $taskList->description }}</p>
                                            @endif
                                        </div>
                                        @can('update', $taskList)
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('task-lists.tasks.create', $taskList) }}" class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                                                    <x-icon type="add" size="md" class="mr-2" />
                                                    Add Task
                                                </a>
                                                <a href="{{ route('task-lists.edit', $taskList) }}"
                                                   class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200"
                                                   title="Edit task list">
                                                    <x-icon type="edit" size="md" />
                                                </a>
                                            </div>
                                        @endcan
                                    </div>

                                    @if($taskList->tasks->isNotEmpty())
                                        <div class="space-y-3">
                                            @foreach($taskList->tasks as $task)
                                                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200">
                                                    <div class="flex items-start justify-between">
                                                        <div class="flex-1">
                                                            <h4 class="font-medium text-gray-900">{{ $task->name }}</h4>
                                                            <div class="mt-2 flex items-center space-x-4 text-sm text-gray-600">
                                                                <div class="flex items-center">
                                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                    </svg>
                                                                    {{ $task->parseInterval() }}
                                                                </div>
                                                                @if($task->optional)
                                                                    <x-badge variant="gray">Optional</x-badge>
                                                                @endif
                                                                @if($task->isOverdue())
                                                                    <x-badge variant="red">Overdue</x-badge>
                                                                @elseif($task->nextDueDate())
                                                                    <x-badge variant="blue">Due {{ $task->nextDueDate()->diffForHumans() }}</x-badge>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="flex space-x-2 ml-4">
                                                            @can('complete', $task)
                                                                <form method="POST" action="{{ route('tasks.scheduled-tasks.store', $task) }}" class="inline">
                                                                    @csrf
                                                                    <button type="submit" class="px-2 py-1 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded transition-colors duration-200 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800" title="Mark Complete">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                        </svg>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                            @can('update', $task)
                                                                <a href="{{ route('task-lists.tasks.edit', [$taskList, $task]) }}" class="text-gray-400 hover:text-gray-600" title="Edit Task">
                                                                    <x-icon type="edit" size="md" />
                                                                </a>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-6 text-gray-500">
                                            <x-icon type="list" size="xl" class="mx-auto text-gray-300 mb-2" />
                                            <p class="text-sm">No tasks yet. <a href="{{ route('task-lists.tasks.create', $taskList) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Add your first task</a>.</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 text-center py-12">
                            <x-icon type="list" size="3xl" class="mx-auto text-gray-300 dark:text-gray-600 mb-4" />
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No task lists yet</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">Get started by creating your first task list to organize work.</p>
                            @can('update', $group)
                                <a href="{{ route('groups.task-lists.create', $group) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                    <x-icon type="add" size="md" class="mr-1" />
                                    Add Your First List
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>

                <!-- Sidebar - Group Info & Members -->
                <div class="mt-8 lg:mt-0">
                    <div class="space-y-6">
                        <!-- Members -->
                        <x-sidebar-box type="neutral" title="Members" icon="group">
                            <div class="space-y-3">
                                @foreach($group->members as $user)
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 bg-indigo-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                                @if($group->isOwnedBy($user))
                                                    <x-badge variant="blue">Owner</x-badge>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @can('invite', $group)
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('groups.invitations.index', $group) }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-900 dark:text-gray-100 font-medium py-2 px-4 rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 w-full text-center inline-flex items-center justify-center">
                                        <x-icon type="invite" size="md" class="mr-1" />
                                        Invite
                                    </a>
                                </div>
                            @endcan
                        </x-sidebar-box>

                        <!-- Statistics -->
                        <x-sidebar-box type="neutral" title="Statistics" icon="chart">
                            <div class="space-y-2 ml-8 mr-8">
                                <div class="flex items-center justify-between">
                                    <span>Members:</span>
                                    <span class="font-medium">{{ $group->members->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Task Lists:</span>
                                    <span class="font-medium">{{ $group->taskLists->count() }}</span>
                                </div>
                                @php
                                    $totalTasks = $group->taskLists->sum(fn($list) => $list->tasks?->count() ?? 0);
                                    $completedTasks = $group->taskLists->sum(fn($list) => $list->tasks?->where('is_completed', true)->count() ?? 0);
                                @endphp
                                <div class="flex items-center justify-between">
                                    <span>Total Tasks:</span>
                                    <span class="font-medium">{{ $totalTasks }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Completed:</span>
                                    <span class="font-medium">{{ $completedTasks }}</span>
                                </div>
                                @if($totalTasks > 0)
                                <div class="pt-2 border-t border-blue-200 dark:border-blue-700">
                                    <div class="flex items-center justify-between">
                                        <span>Overall Progress:</span>
                                        <span class="font-medium">{{ round(($completedTasks / $totalTasks) * 100) }}%</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </x-sidebar-box>

                        <!-- Timeline -->
                        <x-sidebar-box type="neutral" title="Timeline" icon="clock">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span>Created:</span>
                                    <span>{{ $group->created_at->format('M j, Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Last Updated:</span>
                                    <span>{{ $group->updated_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Owner:</span>
                                    <span>{{ $group->owner->name }}</span>
                                </div>
                            </div>
                        </x-sidebar-box>

                        <!-- Pending Invitations -->
                        @if($group->pendingInvitations->isNotEmpty() && $group->isOwnedBy(auth()->user()))
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Pending Invitations</h4>
                                <div class="space-y-2">
                                    @foreach($group->pendingInvitations as $invitation)
                                        <div class="flex items-center justify-between bg-yellow-50 dark:bg-yellow-900/20 px-3 py-2 rounded">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $invitation->email }}</span>
                                            <x-badge variant="gray">Pending</x-badge>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
