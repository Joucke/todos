@extends('layouts.app')

@section('content')
	<h1>Edit a Task</h1>
	<form action="{{ route('task_lists.tasks.update', compact('task_list', 'task')) }}" method="POST">
		{{ csrf_field() }}
		{{ method_field('PATCH') }}
		<input type="text" name="title" value="{{ old('title', $task->title) }}">
		<input type="number" name="interval" value="{{ old('interval', $task->interval) }}">
		<input type="submit" value="Update Task">
	</form>
@endsection