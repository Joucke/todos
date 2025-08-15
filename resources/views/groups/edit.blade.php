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
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Group</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Update your group settings and preferences</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form method="POST" action="{{ route('groups.update', $group) }}" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Name Field -->
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Group Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name', $group->name) }}"
                                       placeholder="e.g., Marketing Team, Project Alpha, Design Squad"
                                       class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                       required>
                                @error('name')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('groups.show', $group) }}"
                                   class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    Update Group
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Panel -->
            <div class="space-y-6">
                <!-- Group Statistics -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-3">Group Statistics</h3>
                            <div class="space-y-2 text-xs text-blue-800 dark:text-blue-200">
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
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Group Timeline
                    </h3>
                    <div class="space-y-2 text-xs text-gray-600 dark:text-gray-400">
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
                </div>

                <!-- Danger Zone -->
                @can('delete', $group)
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
                                Delete this group and all its data. This action cannot be undone and will remove all task lists, tasks, and member relationships.
                            </p>
                            <button type="button"
                                    onclick="document.getElementById('delete-group-modal').classList.remove('hidden')"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg text-xs transition-colors duration-200">
                                Delete Group
                            </button>
                        </div>
                    </div>
                </div>
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
</div>
@endsection
