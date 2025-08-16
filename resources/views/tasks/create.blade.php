@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create Task</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Add a task to <span class="font-medium">{{ $taskList->name }}</span></p>
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
                        <form method="POST" action="{{ route('groups.task-lists.tasks.store', [$group, $taskList]) }}" class="space-y-6">
                            @csrf

                            <!-- Title Field -->
                            <div class="space-y-2">
                                <label for="title" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Task Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="title"
                                       id="title"
                                       value="{{ old('title') }}"
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
                                          class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white resize-none transition-colors duration-200 @error('description') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
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
                                        <option value="low" {{ old('priority', 'medium') == 'low' ? 'selected' : '' }}>Low Priority</option>
                                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium Priority</option>
                                        <option value="high" {{ old('priority', 'medium') == 'high' ? 'selected' : '' }}>High Priority</option>
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
                                           value="{{ old('due_date') }}"
                                           min="{{ date('Y-m-d') }}"
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
                                        <option value="{{ $member->id }}" {{ old('assigned_to', auth()->id()) == $member->id ? 'selected' : '' }}>
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
                                       value="{{ old('tags') }}"
                                       placeholder="frontend, bug, urgent (separated by commas)"
                                       class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('tags') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Separate multiple tags with commas</p>
                                @error('tags')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('task-lists.show', $taskList) }}"
                                   class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    Create Task
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Panel -->
            <div class="space-y-6">
                <!-- Tips Card -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">Task Tips</h3>
                            <ul class="text-xs text-blue-800 dark:text-blue-200 space-y-1">
                                <li>• Use clear, actionable titles</li>
                                <li>• Set realistic due dates</li>
                                <li>• Add detailed descriptions for complex tasks</li>
                                <li>• Use tags for better organization</li>
                                <li>• Assign tasks to specific team members</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Priority Guide -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2m-5 3v12a1 1 0 001 1h4a1 1 0 001-1V7m-5 0h6m-6 0H5m0 0v12a1 1 0 001 1h1m-2-13h2"></path>
                        </svg>
                        Priority Guide
                    </h3>
                    <div class="space-y-3 text-xs">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-gray-700 dark:text-gray-300"><strong>High:</strong> Urgent, blocks other work</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <span class="text-gray-700 dark:text-gray-300"><strong>Medium:</strong> Important, scheduled work</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-gray-700 dark:text-gray-300"><strong>Low:</strong> Nice to have, backlog items</span>
                        </div>
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
                            <h3 class="text-sm font-medium text-purple-900 dark:text-purple-100 mb-2">Adding to</h3>
                            <div class="space-y-1 text-xs text-purple-800 dark:text-purple-200">
                                <p><strong>Group:</strong> {{ $group->name }}</p>
                                <p><strong>Task List:</strong> {{ $taskList->name }}</p>
                                @if($taskList->description)
                                <p class="mt-2 italic">{{ Str::limit($taskList->description, 80) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
