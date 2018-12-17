@extends('layouts.app')

@section('content')
	<h1>{{ $group->title }}</h1>
	<ul>
		@foreach ($group->users as $user)
			<li>{{ $user->name }}</li>
		@endforeach
	</ul>
	@can ('update', $group)
		<a href="{{ route('groups.edit', $group) }}">Edit group</a>
	@endcan
	@can ('delete', $group)
		<form action="{{ route('groups.destroy', $group) }}" method="POST">
			{{ csrf_field() }}
			{{ method_field('DELETE') }}
			<input type="submit" value="Delete group">
		</form>
	@endcan
@endsection