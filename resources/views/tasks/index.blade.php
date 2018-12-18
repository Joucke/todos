@extends('layouts.app')

@section('content')
	<h1>Tasks for {{ $task_list->title }}</h1>
	<ul>
		@foreach ($task_list->tasks as $task)
			<li><a href="{{ route('task_lists.tasks.show', compact('task_list', 'task')) }}">{{ $task->title }}</a></li>
		@endforeach
	</ul>
	<a href="{{ route('task_lists.tasks.create', $task_list) }}">Add Task</a>
@endsection