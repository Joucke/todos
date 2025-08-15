@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $group->name }} - Task Lists</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Organize your work with custom task lists</p>
                        </div>
                    </div>
                    @can('update', $group)
                    <a href="{{ route('groups.task-lists.create', $group) }}"
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Task List
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        @if($taskLists->isEmpty())
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-12 text-center">
                <div class="mx-auto w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">No task lists yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    Get started by creating your first task list to organize your work effectively.
                </p>
                @can('update', $group)
                <a href="{{ route('groups.task-lists.create', $group) }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Your First Task List
                </a>
                @endcan
            </div>
        </div>
        @else
        <!-- Task Lists Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($taskLists as $taskList)
            <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-200 dark:border-gray-700 hover:border-purple-300 dark:hover:border-purple-600 transform hover:-translate-y-1">
                <div class="p-6">
                    <!-- Task List Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">
                                <a href="{{ route('groups.task-lists.show', [$group, $taskList]) }}" class="hover:underline">
                                    {{ $taskList->name }}
                                </a>
                            </h3>
                            @if($taskList->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ $taskList->description }}</p>
                            @endif
                        </div>
                        @can('update', $taskList)
                        <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <a href="{{ route('groups.task-lists.edit', [$group, $taskList]) }}"
                               class="text-gray-400 hover:text-blue-500 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                        @endcan
                    </div>

                    <!-- Progress Bar -->
                    @php
                        $totalTasks = $taskList->tasks_count ?? $taskList->tasks->count();
                        $completedTasks = $taskList->completed_tasks_count ?? $taskList->tasks->where('is_completed', true)->count();
                        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                    @endphp

                    <div class="mb-4">
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="text-gray-600 dark:text-gray-400">Progress</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $completedTasks }}/{{ $totalTasks }} tasks</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-500 to-blue-600 h-2 rounded-full transition-all duration-500"
                                 style="width: {{ $progressPercentage }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalTasks }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Total Tasks</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $completedTasks }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Completed</div>
                        </div>
                    </div>

                    <!-- Last Updated -->
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                        Updated {{ $taskList->updated_at->diffForHumans() }}
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('groups.task-lists.show', [$group, $taskList]) }}"
                       class="block w-full bg-gray-50 dark:bg-gray-700 hover:bg-purple-50 dark:hover:bg-purple-900/20 text-center py-2 px-4 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-all duration-200">
                        View Tasks
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($taskLists->hasPages())
        <div class="mt-8">
            {{ $taskLists->links() }}
        </div>
        @endif
        @endif

        <!-- Back to Group -->
        <div class="mt-8 text-center">
            <a href="{{ route('groups.show', $group) }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to {{ $group->name }}
            </a>
        </div>
    </div>
</div>
@endsection
