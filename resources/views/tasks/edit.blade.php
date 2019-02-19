@extends('layouts.app')

@section('content')
<header class="flex justify-between items-center">
    <h1 class="page-header">
        <a class="text-black no-underline" href="{{ route('groups.show', $task_list->group->id) }}">{{ $task_list->group->title }}</a>
        <span>-</span>
        <a class="text-black no-underline" href="{{ route('task_lists.show', $task_list->id) }}">{{ $task_list->title }}</a>
        <span>-</span>
        <a class="text-black no-underline" href="{{ route('task_lists.tasks.show', compact('task_list', 'task')) }}">{{ $task->title }}</a>
        <span>-</span>
        <span class="">{{ __('tasks.edit') }}</span>
    </h1>
</header>

<div class="py-4">
    <div class="card-container">
        <div class="card-padding-full mb-4">
            <task-form
                class="card bg-white"
                form-title="{{ __('tasks.edit') }}"
                :task-data="{{ $task }}"
                action="{{ route('task_lists.tasks.update', compact('task_list', 'task')) }}"
                method="patch"
                >
            </task-form>
        </div>
    </div>
</div>
@endsection
