@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Task</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Update task in <span class="font-medium">{{ $taskList->name }}</span></p>
                        <div class="flex items-center space-x-2 mt-1 text-sm text-gray-500">
                            <span>{{ $group->name }}</span>
                            <span>•</span>
                            <span>{{ $taskList->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form method="POST" action="{{ route('groups.task-lists.tasks.update', [$group, $taskList, $task]) }}" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Title Field -->
                            <div class="space-y-2">
                                <label for="title" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Task Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="title"
                                       id="title"
                                       value="{{ old('title', $task->title) }}"
                                       placeholder="e.g., Implement user authentication, Fix navigation bug"
                                       class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('title') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                       required>
                                @error('title')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description Field -->
                            <div class="space-y-2">
                                <label for="description" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Description
                                </label>
                                <textarea name="description"
                                          id="description"
                                          rows="4"
                                          placeholder="Provide additional details about this task..."
                                          class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white resize-none transition-colors duration-200 @error('description') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">{{ old('description', $task->description) }}</textarea>
                                @error('description')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Completion Status -->
                            <div class="space-y-2">
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox"
                                           name="is_completed"
                                           value="1"
                                           {{ old('is_completed', $task->is_completed) ? 'checked' : '' }}
                                           class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Mark as completed</span>
                                </label>
                            </div>

                            <!-- Priority and Due Date Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Priority -->
                                <div class="space-y-2">
                                    <label for="priority" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                        Priority
                                    </label>
                                    <select name="priority"
                                            id="priority"
                                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('priority') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                        <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low Priority</option>
                                        <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium Priority</option>
                                        <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High Priority</option>
                                    </select>
                                    @error('priority')
                                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Due Date -->
                                <div class="space-y-2">
                                    <label for="due_date" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                        Due Date
                                    </label>
                                    <input type="date"
                                           name="due_date"
                                           id="due_date"
                                           value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                                           class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('due_date') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                    @error('due_date')
                                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Assigned To (if user can assign) -->
                            @if($group->members->count() > 1)
                            <div class="space-y-2">
                                <label for="assigned_to" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Assign To
                                </label>
                                <select name="assigned_to"
                                        id="assigned_to"
                                        class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('assigned_to') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                    <option value="">Unassigned</option>
                                    @foreach($group->members as $member)
                                        <option value="{{ $member->id }}" {{ old('assigned_to', $task->assigned_to) == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }} {{ $member->id == auth()->id() ? '(You)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            @endif

                            <!-- Tags -->
                            <div class="space-y-2">
                                <label for="tags" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Tags
                                </label>
                                <input type="text"
                                       name="tags"
                                       id="tags"
                                       value="{{ old('tags', $task->tags ? implode(', ', $task->tags) : '') }}"
                                       placeholder="frontend, bug, urgent (separated by commas)"
                                       class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('tags') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Separate multiple tags with commas</p>
                                @error('tags')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('groups.task-lists.show', [$group, $taskList]) }}"
                                   class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    Update Task
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Panel -->
            <div class="space-y-6">
                <!-- Task Status -->
                <div class="bg-gradient-to-br from-{{ $task->is_completed ? 'green' : 'orange' }}-50 to-{{ $task->is_completed ? 'emerald' : 'red' }}-100 dark:from-{{ $task->is_completed ? 'green' : 'orange' }}-900/20 dark:to-{{ $task->is_completed ? 'emerald' : 'red' }}-900/20 rounded-xl p-6 border border-{{ $task->is_completed ? 'green' : 'orange' }}-200 dark:border-{{ $task->is_completed ? 'green' : 'orange' }}-800">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            @if($task->is_completed)
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            @else
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-{{ $task->is_completed ? 'green' : 'orange' }}-900 dark:text-{{ $task->is_completed ? 'green' : 'orange' }}-100 mb-2">
                                {{ $task->is_completed ? 'Task Completed' : 'Task In Progress' }}
                            </h3>
                            <p class="text-xs text-{{ $task->is_completed ? 'green' : 'orange' }}-800 dark:text-{{ $task->is_completed ? 'green' : 'orange' }}-200">
                                @if($task->is_completed)
                                    This task was marked as completed {{ $task->updated_at->diffForHumans() }}.
                                @else
                                    This task is currently in progress and needs attention.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Task History -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Task Timeline
                    </h3>
                    <div class="space-y-3 text-xs text-gray-600 dark:text-gray-400">
                        <div class="flex items-center justify-between">
                            <span>Created</span>
                            <span>{{ $task->created_at->format('M j, Y g:i A') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Last Updated</span>
                            <span>{{ $task->updated_at->format('M j, Y g:i A') }}</span>
                        </div>
                        @if($task->due_date)
                        <div class="flex items-center justify-between">
                            <span>Due Date</span>
                            <span class="{{ $task->due_date->isPast() && !$task->is_completed ? 'text-red-600 dark:text-red-400 font-medium' : '' }}">
                                {{ $task->due_date->format('M j, Y') }}
                                @if($task->due_date->isPast() && !$task->is_completed)
                                    (Overdue)
                                @endif
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Context Info -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-100 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-6 border border-purple-200 dark:border-purple-800">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-purple-900 dark:text-purple-100 mb-2">Task Location</h3>
                            <div class="space-y-1 text-xs text-purple-800 dark:text-purple-200">
                                <p><strong>Group:</strong> {{ $group->name }}</p>
                                <p><strong>Task List:</strong> {{ $taskList->name }}</p>
                                @if($task->assigned_to && $task->assignedUser)
                                <p><strong>Assigned to:</strong> {{ $task->assignedUser->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                @can('delete', $task)
                <div class="bg-gradient-to-br from-red-50 to-pink-100 dark:from-red-900/20 dark:to-pink-900/20 rounded-xl p-6 border border-red-200 dark:border-red-800">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.866-.833-2.634 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-red-900 dark:text-red-100 mb-2">Danger Zone</h3>
                            <p class="text-xs text-red-800 dark:text-red-200 mb-4">
                                Delete this task permanently. This action cannot be undone.
                            </p>
                            <form method="POST" action="{{ route('groups.task-lists.tasks.destroy', [$group, $taskList, $task]) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this task? This action cannot be undone.')"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg text-xs transition-colors duration-200">
                                    Delete Task
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
