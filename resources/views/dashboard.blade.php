@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-700 overflow-hidden shadow-xl sm:rounded-2xl mb-8">
            <div class="px-6 py-8 sm:px-8 sm:py-12">
                <div class="flex items-center justify-between">
                    <div class="text-white">
                        <h1 class="text-3xl sm:text-4xl font-bold mb-2">
                            Welcome back, {{ auth()->user()->name }}!
                        </h1>
                        <p class="text-lg text-purple-100 mb-4">
                            {{ now()->format('l, F j, Y') }}
                        </p>
                        <div class="flex items-center space-x-6 text-sm text-purple-100">
                            <div class="flex items-center">
                                <x-icon type="group" size="lg" class="mr-2" />
                                {{ auth()->user()->groups->count() }} {{ Str::plural('Group', auth()->user()->groups->count()) }}
                            </div>
                        </div>
                    </div>
                    <div class="hidden sm:block">
                        <div class="w-32 h-32 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <x-icon type="achievement" size="4xl" class="text-white" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($tabs->count())
            <!-- Groups with Tasks -->
            <div class="space-y-8">
                @foreach($tabs as $group)
                    @if($tasks->has($group->id))
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6 sm:p-8">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $group->name }}
                                    </h2>
                                    <a href="{{ route('groups.show', $group) }}"
                                       class="text-indigo-600 hover:text-indigo-800 font-medium">
                                        View Group →
                                    </a>
                                </div>

                                @foreach($tasks[$group->id] as $period => $periodTasks)
                                    <div class="mb-8">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                                            {{ ucwords(str_replace(['1-', '2-', '3-', '4-', '5-', '6-', '_'], '', $period)) }}
                                        </h3>

                                        <div class="grid gap-4">
                                            @foreach($periodTasks as $task)
                                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                                    <div class="flex items-center justify-between">
                                                        <div>
                                                            <h4 class="font-medium text-gray-900 dark:text-white">
                                                                {{ $task->title }}
                                                            </h4>
                                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                                {{ $task->taskList->name }}
                                                            </p>
                                                        </div>

                                                        @if($task->scheduledTasks->where('completed_at', null)->first())
                                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                {{ $task->scheduledTasks->where('completed_at', null)->first()->scheduled_at->format('M j, Y') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <!-- No Groups State -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-12 text-center">
                    <x-icon type="group" size="4xl" class="mx-auto text-gray-400 mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No groups yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Get started by creating your first group to organize your tasks.</p>
                    <a href="{{ route('groups.create') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        Create Your First Group
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
