@extends('layouts.app')

@section('content')
	<h1>Create Group</h1>
	<form action="{{ route('groups.store') }}" method="POST">
		{{ csrf_field() }}
		<input type="text" name="title" value="{{ old('title') }}">
		<input type="submit" value="Add group">
	</form>
@endsection