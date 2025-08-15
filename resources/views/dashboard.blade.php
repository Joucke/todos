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
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ auth()->user()->groups->count() }} {{ Str::plural('Group', auth()->user()->groups->count()) }}
                            </div>
                        </div>
                    </div>
                    <div class="hidden sm:block">
                        <div class="w-32 h-32 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
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
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
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
