@extends('layouts.app')

@section('content')
	<h1>Create a Task</h1>
	<form action="{{ route('task_lists.tasks.store', $task_list) }}" method="POST">
		{{ csrf_field() }}
		<input type="text" name="title" value="{{ old('title') }}">
		<input type="number" name="interval" value="{{ old('interval') }}">
		<input type="submit" value="Add Task">
	</form>
@endsection