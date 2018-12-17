@extends('layouts.app')

@section('content')
	<h1>Task lists</h1>
	<ul>
		@foreach ($task_lists as $task_list)
			<li><a href="{{ route('task_lists.show', $task_list) }}">{{ $task_list->title }}</a></li>
		@endforeach
	</ul>
	<a href="{{ route('task_lists.create') }}">Create Task List</a>
@endsection