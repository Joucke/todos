@extends('layouts.app')

@section('content')
	<h1>Groups</h1>
	<ul>
		@foreach ($groups as $group)
			<li><a href="{{ route('groups.show', $group) }}">{{ $group->title }}</a></li>
		@endforeach
	</ul>
	<a href="{{ route('groups.create') }}">Create group</a>
@endsection