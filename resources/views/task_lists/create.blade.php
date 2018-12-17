@extends('layouts.app')

@section('content')
	<h1>Create a Task List</h1>
	<form action="{{ route('task_lists.store') }}" method="POST">
		{{ csrf_field() }}
		<input type="text" name="title" value="{{ old('title') }}">
		<select name="group_id">
			@foreach ($groups as $group)
				<option value="{{ $group->id }}">{{ $group->title }}</option>
			@endforeach
		</select>
		<input type="submit" value="Add Task List">
	</form>
@endsection