@extends('layouts.app')

@section('content')
	<h1>{{ $task->title }}</h1>
	@can('view', $task_list)
		<a href="{{ route('task_lists.tasks.edit', compact('task_list', 'task')) }}">Edit task list</a>
	@endcan
	@can ('view', $task_list)
		<form action="{{ route('task_lists.tasks.destroy', compact('task_list', 'task')) }}" method="POST">
			{{ csrf_field() }}
			{{ method_field('DELETE') }}
			<input type="submit" value="Delete Task">
		</form>
	@endcan
@endsection