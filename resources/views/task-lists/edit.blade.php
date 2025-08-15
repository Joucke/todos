@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Task List</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Update <span class="font-medium">{{ $taskList->name }}</span> in {{ $group->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form method="POST" action="{{ route('task-lists.update', $taskList) }}" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Name Field -->
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Task List Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name', $taskList->name) }}"
                                       placeholder="e.g., Sprint Backlog, Bug Fixes, Feature Requests"
                                       class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                       required>
                                @error('name')
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
                                          placeholder="Describe the purpose of this task list..."
                                          class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white resize-none transition-colors duration-200 @error('description') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">{{ old('description', $taskList->description) }}</textarea>
                                @error('description')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Color Selection -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Color Theme
                                </label>
                                <div class="grid grid-cols-4 gap-3">
                                    @php
                                        $colors = [
                                            'blue' => ['bg-blue-500', 'Blue'],
                                            'purple' => ['bg-purple-500', 'Purple'],
                                            'green' => ['bg-green-500', 'Green'],
                                            'yellow' => ['bg-yellow-500', 'Yellow'],
                                            'red' => ['bg-red-500', 'Red'],
                                            'pink' => ['bg-pink-500', 'Pink'],
                                            'indigo' => ['bg-indigo-500', 'Indigo'],
                                            'gray' => ['bg-gray-500', 'Gray']
                                        ];
                                    @endphp
                                    @foreach($colors as $color => $details)
                                    <label class="relative flex items-center justify-center cursor-pointer">
                                        <input type="radio"
                                               name="color"
                                               value="{{ $color }}"
                                               {{ old('color', $taskList->color ?? 'blue') == $color ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-12 h-12 {{ $details[0] }} rounded-lg flex items-center justify-center transition-all duration-200 ring-2 ring-transparent peer-checked:ring-2 peer-checked:ring-purple-500 peer-checked:ring-offset-2 dark:peer-checked:ring-offset-gray-800 hover:scale-105 transform">
                                            <svg class="w-5 h-5 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                                @error('color')
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
                                    Update Task List
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Panel -->
            <div class="space-y-6">
                <!-- Task List Stats -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-3">Task List Overview</h3>
                            @php
                                $totalTasks = $taskList->tasks->count();
                                $completedTasks = $taskList->tasks->where('is_completed', true)->count();
                            @endphp
                            <div class="space-y-2 text-xs text-blue-800 dark:text-blue-200">
                                <div class="flex items-center justify-between">
                                    <span>Total Tasks:</span>
                                    <span class="font-medium">{{ $totalTasks }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Completed:</span>
                                    <span class="font-medium">{{ $completedTasks }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>In Progress:</span>
                                    <span class="font-medium">{{ $totalTasks - $completedTasks }}</span>
                                </div>
                                @if($totalTasks > 0)
                                <div class="pt-2 border-t border-blue-200 dark:border-blue-700">
                                    <div class="flex items-center justify-between">
                                        <span>Progress:</span>
                                        <span class="font-medium">{{ round(($completedTasks / $totalTasks) * 100) }}%</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Timeline
                    </h3>
                    <div class="space-y-2 text-xs text-gray-600 dark:text-gray-400">
                        <div class="flex items-center justify-between">
                            <span>Created:</span>
                            <span>{{ $taskList->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Last Updated:</span>
                            <span>{{ $taskList->updated_at->diffForHumans() }}</span>
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
                            <h3 class="text-sm font-medium text-purple-900 dark:text-purple-100 mb-2">Group Context</h3>
                            <div class="space-y-1 text-xs text-purple-800 dark:text-purple-200">
                                <p><strong>Group:</strong> {{ $group->name }}</p>
                                <p><strong>Members:</strong> {{ $group->members->count() }} {{ Str::plural('person', $group->members->count()) }}</p>
                                <p><strong>Total Lists:</strong> {{ $group->taskLists->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                @can('delete', $taskList)
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
                                Delete this task list and all its tasks. This action cannot be undone.
                            </p>
                            <form method="POST" action="{{ route('task-lists.destroy', $taskList) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this task list and all its tasks? This action cannot be undone.')"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg text-xs transition-colors duration-200">
                                    Delete Task List
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
