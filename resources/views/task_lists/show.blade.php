@extends('layouts.app')

@section('content')
	<h1>{{ $task_list->title }}</h1>
	<ul>
		@foreach ($task_list->tasks as $task)
			<li><a href="{{ route('task_lists.tasks.show', compact('task_list', 'task')) }}">{{ $task->title }}</a></li>
		@endforeach
	</ul>
	@can('update', $task_list)
		<a href="{{ route('task_lists.edit', $task_list) }}">Edit task list</a>
	@endcan
	@can ('delete', $task_list)
		<form action="{{ route('task_lists.destroy', $task_list) }}" method="POST">
			{{ csrf_field() }}
			{{ method_field('DELETE') }}
			<input type="submit" value="Delete Task List">
		</form>
	@endcan
@endsection