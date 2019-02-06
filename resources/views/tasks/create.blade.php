@extends('layouts.app')

@section('content')
<div class="flex flex-wrap justify-around md:-mx-2">
    <div class="w-full md:p-2">
        <div class="rounded bg-white shadow-md">
            <div class="bg-grey-lighter border-b border-grey rounded-t py-2 px-4 font-semibold">{{ __('tasks.create') }}</div>

        	<create-task-form class="pt-2 pb-4 px-4" action="{{ route('task_lists.tasks.store', $task_list) }}"></create-task-form>
        </div>
    </div>
</div>
@endsection
