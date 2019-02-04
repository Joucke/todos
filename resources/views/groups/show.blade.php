@extends('layouts.app')

@section('content')
<h1>{{ $group->title }}</h1>

<div class="flex flex-wrap md:-mx-2">
    <div class="w-full md:w-1/2 p-2">
        <div class="rounded bg-white shadow-md">
        	<div class="bg-grey-lighter border-b border-grey rounded-t py-2 px-4 font-semibold">{{ __('Task Lists') }}</div>

        	<div class="pt-2 pb-4 px-4">
        		<ul class="list-reset pb-4 leading-normal">
        			@foreach ($group->task_lists as $list)
        				<li><a href="{{ route('task_lists.show', ['task_list' => $list]) }}">{{ $list->title }}</a></li>
        			@endforeach
        		</ul>
        		<a class="bg-blue hover:bg-blue-dark text-white no-underline py-2 px-4 rounded" href="{{ route('task_lists.create') }}">{{ __('add task list') }}</a>
        	</div>
        </div>
    </div>

    <div class="w-full md:w-1/2 p-2">
        <div class="rounded bg-white shadow-md">
        	<div class="bg-grey-lighter border-b border-grey rounded-t py-2 px-4 font-semibold">{{ __('Group members') }}</div>

        	<div class="pt-2 pb-4 px-4">
        		<ul class="list-reset pb-4 leading-normal">
        			@foreach ($group->users as $user)
        				<li>{{ $user->name }}</li>
        			@endforeach
        		</ul>
        		@can ('update', $group)
        			<form action="{{ route('groups.users.store', compact('group')) }}" method="POST">
        				{{ csrf_field() }}
        				{{-- TODO: replace this with a simple input field for email, to be sent w/ json to invite a user --}}
        				<select name="user_id">
        					@foreach ($users as $user)
        						@if (!$group->users->contains('id', $user->id))
        							<option value="{{ $user->id }}">{{ $user->name }}</option>
        						@endif
        					@endforeach
        				</select>
        				<input class="bg-blue hover:bg-blue-dark text-white no-underline py-2 px-4 rounded" type="submit" value="{{ __('add user') }}">
        			</form>
        		@endcan
        	</div>
        </div>
    </div>
</div>
@can ('update', $group)
	<a href="{{ route('groups.edit', $group) }}">Edit group</a>
@endcan
@can ('delete', $group)
	<form class="inline" action="{{ route('groups.destroy', $group) }}" method="POST">
		{{ csrf_field() }}
		{{ method_field('DELETE') }}
		<input type="submit" value="Delete group">
	</form>
@endcan
@endsection
