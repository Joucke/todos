@extends('layouts.app')

@section('content')
	<h1>Edit Group</h1>
	<form action="{{ route('groups.update', $group) }}" method="POST">
		{{ csrf_field() }}
		{{ method_field('PATCH') }}
		<input type="text" name="title" value="{{ old('title', $group->title) }}">
		<input type="submit" value="Update group">
	</form>
@endsection