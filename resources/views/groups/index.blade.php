@extends('layouts.app')

@section('content')
<h1>{{ __('Groups') }}</h1>
<div class="py-4">
    <ul class="list-reset">
    	@foreach ($groups as $group)
    		<li><a href="{{ route('groups.show', $group) }}">{{ $group->title }}</a></li>
    	@endforeach
    </ul>
</div>
<div>
    <a class="bg-blue hover:bg-blue-dark text-white no-underline py-2 px-4 rounded" href="{{ route('groups.create') }}">Create group</a>
</div>
@endsection
