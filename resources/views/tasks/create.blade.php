@extends('layouts.app')

@section('content')
<header class="flex justify-between items-center">
    <h1 class="page-header">
        <a class="text-black no-underline" href="{{ route('groups.show', $task_list->group->id) }}">{{ $task_list->group->title }}</a>
        <span>-</span>
        <a class="text-black no-underline" href="{{ route('task_lists.show', $task_list->id) }}">{{ $task_list->title }}</a>
        <span>-</span>
        <span class="">{{ __('tasks.create') }}</span>
    </h1>
</header>

<div class="card-container py-4">
    <div class="card-padding-full mb-4">
    	<task-form
            @if (app()->environment() !== 'Production')
                dusk="task-form-component"
            @endif
            class="card bg-white"
            form-title="{{ __('tasks.create') }}"
            :task-data="{title:'',interval:1,data:{}}"
            action="{{ route('task_lists.tasks.store', $task_list) }}"
            >
        </task-form>
    </div>
</div>
@endsection
