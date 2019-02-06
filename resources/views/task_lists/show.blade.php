@extends('layouts.app')

@section('content')
<header class="flex justify-between items-center">
    <h1 class="page-header">{{ $task_list->title }}</h1>
    <div class="flex">
        @can ('update', $task_list)
            <a class="button button-blue button-secondary button-xs" href="{{ route('task_lists.edit', $task_list) }}">
                {{ __('task_lists.edit') }}
            </a>
        @endcan
        @can ('delete', $task_list)
            <form class="flex ml-2" action="{{ route('task_lists.destroy', $task_list) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <input class="button button-red button-secondary button-xs" type="submit" value="{{ __('task_lists.delete') }}">
            </form>
        @endcan
    </div>
</header>
<div class="py-4">
    <div class="card-container">
        <div class="card-padding mb-4">
        	<div class="card">
                <div class="card-header bg-grey-lighter border-b">
                    <p class="font-semibold">{{ __('tasks.tasks') }}</p>
                    <a class="button button-blue button-secondary button-xs" href="{{ route('task_lists.tasks.create', $task_list) }}">{{ __('tasks.create') }}</a>
                </div>

                <div class="card-body bg-white">
            		<ul class="list-reset leading-normal">
            			@foreach ($task_list->tasks as $task)
    						<li>
                                <a href="{{ route('task_lists.tasks.show', compact('task_list', 'task')) }}">{{ $task->title }}</a>
                                {{-- TODO: add more task specifics here --}}
                            </li>
            			@endforeach
            		</ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
