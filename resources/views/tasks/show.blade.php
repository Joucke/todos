@extends('layouts.app')

@section('content')
<header class="flex justify-between items-center">
	<h1 class="page-header">
        <a class="text-black no-underline" href="{{ route('groups.show', $task_list->group->id) }}">{{ $task_list->group->title }}</a>
        <span>-</span>
        <a class="text-black no-underline" href="{{ route('task_lists.show', $task_list->id) }}">{{ $task_list->title }}</a>
        <span>-</span>
		{{ $task->title }}
	</h1>
	<div class="flex">
		@can('view', $task_list)
			<a class="button button-blue button-secondary button-xs" href="{{ route('task_lists.tasks.edit', compact('task_list', 'task')) }}">
				{{ __('tasks.edit') }}
			</a>
		@endcan
		@can ('view', $task_list)
			<form class="flex ml-2" action="{{ route('task_lists.tasks.destroy', compact('task_list', 'task')) }}" method="POST">
				@csrf
				@method('DELETE')
				<input class="button button-red button-secondary button-xs" type="submit" value="{{ __('tasks.delete') }}">
			</form>
		@endcan
	</div>
</header>
<div class="py-4">
	<div class="card-container">
		<task-card :task-data="{{ $task }}"></task-card>
	</div>
</div>
@endsection
