@extends('layouts.app')

@section('content')
	<h1>Edit a Task List</h1>
	<form action="{{ route('task_lists.update', $task_list) }}" method="POST">
		{{ csrf_field() }}
		{{ method_field('PATCH') }}
		<input type="text" name="title" value="{{ old('title', $task_list->title) }}">
		<select name="group_id">
			@foreach ($groups as $group)
				<option value="{{ $group->id }}"
					{{ $task_list->group_id == $group->id ? 'selected' : '' }}
					>{{ $group->title }}</option>
			@endforeach
		</select>
		<input type="submit" value="Update Task List">
	</form>
@endsection