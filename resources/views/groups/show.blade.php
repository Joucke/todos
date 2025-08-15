<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $group->name }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ $group->members->count() }} {{ Str::plural('member', $group->members->count()) }} •
                    {{ $group->taskLists->count() }} {{ Str::plural('list', $group->taskLists->count()) }}
                </p>
            </div>
            <div class="flex space-x-3">
                @can('update', $group)
                    <a href="{{ route('groups.task-lists.create', $group) }}" class="btn-secondary inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create List
                    </a>
                    <a href="{{ route('groups.edit', $group) }}" class="btn-primary inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Settings
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Messages -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="lg:grid lg:grid-cols-4 lg:gap-8">
                <!-- Main Content - Task Lists -->
                <div class="lg:col-span-3">
                    @if($group->taskLists->isNotEmpty())
                        <div class="space-y-8">
                            @foreach($group->taskLists as $taskList)
                                <div class="card">
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $taskList->name }}</h3>
                                        @can('update', $taskList)
                                            <div class="flex space-x-2">
                                                <a href="{{ route('task-lists.tasks.create', $taskList) }}" class="btn-secondary text-sm">
                                                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                    </svg>
                                                    Add Task
                                                </a>
                                                <a href="{{ route('task-lists.edit', $taskList) }}" class="text-gray-400 hover:text-gray-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
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
                                                                    <span class="badge badge-gray">Optional</span>
                                                                @endif
                                                                @if($task->isOverdue())
                                                                    <span class="badge badge-red">Overdue</span>
                                                                @elseif($task->nextDueDate())
                                                                    <span class="badge badge-blue">Due {{ $task->nextDueDate()->diffForHumans() }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="flex space-x-2 ml-4">
                                                            @can('complete', $task)
                                                                <form method="POST" action="{{ route('tasks.scheduled-tasks.store', $task) }}" class="inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn-primary text-sm" title="Mark Complete">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                        </svg>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                            @can('update', $task)
                                                                <a href="{{ route('task-lists.tasks.edit', [$taskList, $task]) }}" class="text-gray-400 hover:text-gray-600" title="Edit Task">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                    </svg>
                                                                </a>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-6 text-gray-500">
                                            <svg class="mx-auto h-8 w-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <p class="text-sm">No tasks yet. <a href="{{ route('task-lists.tasks.create', $taskList) }}" class="text-blue-600 hover:text-blue-800">Add your first task</a>.</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="card text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No task lists yet</h3>
                            <p class="text-gray-600 mb-6">Get started by creating your first task list to organize work.</p>
                            @can('update', $group)
                                <a href="{{ route('groups.task-lists.create', $group) }}" class="btn-primary inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Create First List
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>

                <!-- Sidebar - Group Info & Members -->
                <div class="mt-8 lg:mt-0">
                    <div class="space-y-6">
                        <!-- Group Members -->
                        <div class="card">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Members</h4>
                            <div class="space-y-3">
                                @foreach($group->members as $user)
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                                @if($group->isOwnedBy($user))
                                                    <span class="badge badge-blue">Owner</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @can('invite', $group)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <a href="{{ route('groups.invitations.index', $group) }}" class="btn-secondary w-full text-center inline-flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                        </svg>
                                        Invite
                                    </a>
                                </div>
                            @endcan
                        </div>

                        <!-- Pending Invitations -->
                        @if($group->pendingInvitations->isNotEmpty() && $group->isOwnedBy(auth()->user()))
                            <div class="card">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Pending Invitations</h4>
                                <div class="space-y-2">
                                    @foreach($group->pendingInvitations as $invitation)
                                        <div class="flex items-center justify-between bg-yellow-50 px-3 py-2 rounded">
                                            <span class="text-sm text-gray-700">{{ $invitation->email }}</span>
                                            <span class="badge badge-gray">Pending</span>
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
