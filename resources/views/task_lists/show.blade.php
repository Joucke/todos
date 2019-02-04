@extends('layouts.app')

@section('content')
<div class="flex flex-wrap justify-around">
    <div class="w-full md:w-1/2 p-2">
        <div class="rounded bg-white shadow-md">
        	<div class="bg-grey-lighter border-b border-grey rounded-t py-2 px-4 font-semibold">{{ $task_list->title }}</div>

        	<div class="pt-2 pb-4 px-4">
        		<ul class="list-reset pb-4 leading-normal">
        			@foreach ($task_list->tasks as $task)
						<li><a href="{{ route('task_lists.tasks.show', compact('task_list', 'task')) }}">{{ $task->title }}</a></li>
        			@endforeach
        		</ul>
				<a class="bg-blue hover:bg-blue-dark text-white no-underline py-2 px-4 rounded" href="{{ route('task_lists.tasks.create', $task_list) }}">{{ __('add task') }}</a>
        	</div>
        </div>
    </div>
</div>
<div class="flex flex-wrap justify-around">
	<div class="w-full md:w-1/2 p-2 flex justify-between">
		@can('update', $task_list)
			<a class="bg-blue hover:bg-blue-dark text-white no-underline py-2 px-4 rounded" href="{{ route('task_lists.edit', $task_list) }}">Edit task list</a>
		@endcan
		@can ('delete', $task_list)
			<form class="inline" action="{{ route('task_lists.destroy', $task_list) }}" method="POST">
				{{ csrf_field() }}
				{{ method_field('DELETE') }}
				<input type="submit" value="Delete Task List">
			</form>
		@endcan
	</div>
</div>
@endsection
