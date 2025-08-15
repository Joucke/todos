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
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8l2 2 4-4"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $taskList->name }}</h1>
                            @if($taskList->description)
                            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $taskList->description }}</p>
                            @endif
                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                <span>Group: <span class="font-medium">{{ $group->name }}</span></span>
                                <span>•</span>
                                <span>Updated {{ $taskList->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @can('create', [App\Models\Task::class, $taskList])
                        <a href="{{ route('task-lists.tasks.create', $taskList) }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Task
                        </a>
                        @endcan
                        @can('update', $taskList)
                        <a href="{{ route('task-lists.edit', $taskList) }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Progress Section -->
                @php
                    $totalTasks = $taskList->tasks->count();
                    $completedTasks = $taskList->tasks->where('is_completed', true)->count();
                    $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                @endphp

                <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalTasks }}</div>
                        <div class="text-sm text-blue-700 dark:text-blue-300">Total Tasks</div>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $completedTasks }}</div>
                        <div class="text-sm text-green-700 dark:text-green-300">Completed</div>
                    </div>
                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-lg p-4 border border-orange-200 dark:border-orange-800">
                        <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $totalTasks - $completedTasks }}</div>
                        <div class="text-sm text-orange-700 dark:text-orange-300">Remaining</div>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $progressPercentage }}%</div>
                        <div class="text-sm text-purple-700 dark:text-purple-300">Complete</div>
                    </div>
                </div>

                @if($totalTasks > 0)
                <div class="mt-4">
                    <div class="flex items-center justify-between text-sm mb-2">
                        <span class="text-gray-600 dark:text-gray-400">Overall Progress</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $completedTasks }} of {{ $totalTasks }} tasks completed</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-500 to-blue-600 h-3 rounded-full transition-all duration-500"
                             style="width: {{ $progressPercentage }}%;">
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if($taskList->tasks->isEmpty())
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-12 text-center">
                <div class="mx-auto w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">No tasks yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    Start organizing your work by adding the first task to this list.
                </p>
                @can('create', [App\Models\Task::class, $taskList])
                <a href="{{ route('task-lists.tasks.create', $taskList) }}"
                   class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Your First Task
                </a>
                @endcan
            </div>
        </div>
        @else
        <!-- Tasks List -->
        <div class="space-y-4">
            <!-- Filter/Sort Controls -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <button type="button" id="show-all" class="filter-btn active px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300">
                                All ({{ $totalTasks }})
                            </button>
                            <button type="button" id="show-pending" class="filter-btn px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                Pending ({{ $totalTasks - $completedTasks }})
                            </button>
                            <button type="button" id="show-completed" class="filter-btn px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                Completed ({{ $completedTasks }})
                            </button>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-500 dark:text-gray-400">Sort by:</label>
                            <select id="sort-tasks" class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="created_at">Date Created</option>
                                <option value="title">Title</option>
                                <option value="priority">Priority</option>
                                <option value="due_date">Due Date</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Items -->
            <div class="space-y-3" id="tasks-container">
                @foreach($taskList->tasks->sortBy('created_at') as $task)
                <div class="task-item bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200 {{ $task->is_completed ? 'task-completed opacity-75' : 'task-pending' }}"
                     data-task-id="{{ $task->id }}"
                     data-completed="{{ $task->is_completed ? 'true' : 'false' }}"
                     data-created="{{ $task->created_at->timestamp }}"
                     data-title="{{ strtolower($task->title) }}"
                     data-priority="{{ $task->priority ?? 'medium' }}"
                     data-due-date="{{ $task->due_date ? $task->due_date->timestamp : '9999999999' }}">

                    <div class="p-4">
                        <div class="flex items-start space-x-4">
                            <!-- Checkbox -->
                            <div class="flex-shrink-0 mt-1">
                                <form method="POST" action="{{ route('groups.task-lists.tasks.toggle', [$group, $taskList, $task]) }}" class="task-toggle-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="flex items-center justify-center w-5 h-5 border-2 rounded transition-all duration-200 {{ $task->is_completed ? 'bg-green-500 border-green-500' : 'border-gray-300 hover:border-green-400' }}">
                                        @if($task->is_completed)
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        @endif
                                    </button>
                                </form>
                            </div>

                            <!-- Task Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-base font-medium text-gray-900 dark:text-white {{ $task->is_completed ? 'line-through' : '' }}">
                                            {{ $task->title }}
                                        </h3>
                                        @if($task->description)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 {{ $task->is_completed ? 'line-through' : '' }}">
                                            {{ $task->description }}
                                        </p>
                                        @endif

                                        <!-- Task Meta -->
                                        <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                            @if($task->priority)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                {{ $task->priority === 'high' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : '' }}
                                                {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' : '' }}
                                                {{ $task->priority === 'low' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : '' }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                            @endif

                                            @if($task->due_date)
                                            <span class="inline-flex items-center text-gray-500 dark:text-gray-400">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                Due {{ $task->due_date->format('M j, Y') }}
                                            </span>
                                            @endif

                                            <span>Created {{ $task->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    @canany(['update', 'delete'], $task)
                                    <div class="flex items-center space-x-2 ml-4">
                                        @can('update', $task)
                                        <a href="{{ route('groups.task-lists.tasks.edit', [$group, $taskList, $task]) }}"
                                           class="text-gray-400 hover:text-blue-500 transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        @endcan
                                        @can('delete', $task)
                                        <form method="POST" action="{{ route('groups.task-lists.tasks.destroy', [$group, $taskList, $task]) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Are you sure you want to delete this task?')"
                                                    class="text-gray-400 hover:text-red-500 transition-colors duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                    @endcanany
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Back Navigation -->
        <div class="mt-8 text-center">
            <a href="{{ route('groups.show', $group) }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Task Lists
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    const tasksContainer = document.getElementById('tasks-container');
    const taskItems = tasksContainer.querySelectorAll('.task-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active', 'bg-purple-100', 'dark:bg-purple-900', 'text-purple-700', 'dark:text-purple-300'));
            this.classList.add('active', 'bg-purple-100', 'dark:bg-purple-900', 'text-purple-700', 'dark:text-purple-300');

            // Show/hide tasks
            const filter = this.id.replace('show-', '');
            taskItems.forEach(task => {
                const isCompleted = task.dataset.completed === 'true';

                if (filter === 'all') {
                    task.style.display = 'block';
                } else if (filter === 'completed' && isCompleted) {
                    task.style.display = 'block';
                } else if (filter === 'pending' && !isCompleted) {
                    task.style.display = 'block';
                } else {
                    task.style.display = 'none';
                }
            });
        });
    });

    // Sort functionality
    const sortSelect = document.getElementById('sort-tasks');
    sortSelect.addEventListener('change', function() {
        const sortBy = this.value;
        const tasks = Array.from(taskItems);

        tasks.sort((a, b) => {
            let aVal, bVal;

            switch(sortBy) {
                case 'title':
                    aVal = a.dataset.title;
                    bVal = b.dataset.title;
                    return aVal.localeCompare(bVal);
                case 'priority':
                    const priorityOrder = { high: 3, medium: 2, low: 1 };
                    aVal = priorityOrder[a.dataset.priority] || 2;
                    bVal = priorityOrder[b.dataset.priority] || 2;
                    return bVal - aVal; // High to low
                case 'due_date':
                    aVal = parseInt(a.dataset.dueDate);
                    bVal = parseInt(b.dataset.dueDate);
                    return aVal - bVal; // Earliest first
                default: // created_at
                    aVal = parseInt(a.dataset.created);
                    bVal = parseInt(b.dataset.created);
                    return bVal - aVal; // Newest first
            }
        });

        // Re-append sorted tasks
        tasks.forEach(task => tasksContainer.appendChild(task));
    });

    // Task toggle with AJAX
    document.querySelectorAll('.task-toggle-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update task appearance
                    const taskItem = this.closest('.task-item');
                    const button = this.querySelector('button');
                    const title = taskItem.querySelector('h3');
                    const description = taskItem.querySelector('p');

                    if (data.completed) {
                        taskItem.classList.add('task-completed', 'opacity-75');
                        taskItem.classList.remove('task-pending');
                        taskItem.dataset.completed = 'true';
                        button.innerHTML = '<svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>';
                        button.classList.add('bg-green-500', 'border-green-500');
                        title.classList.add('line-through');
                        if (description) description.classList.add('line-through');
                    } else {
                        taskItem.classList.remove('task-completed', 'opacity-75');
                        taskItem.classList.add('task-pending');
                        taskItem.dataset.completed = 'false';
                        button.innerHTML = '';
                        button.classList.remove('bg-green-500', 'border-green-500');
                        title.classList.remove('line-through');
                        if (description) description.classList.remove('line-through');
                    }

                    // Optionally refresh page to update counters
                    setTimeout(() => location.reload(), 1000);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>
@endpush
@endsection
