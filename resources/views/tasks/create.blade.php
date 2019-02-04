@extends('layouts.app')

@section('content')
<div class="flex flex-wrap justify-around -mx-2">
    <div class="w-full p-2">
        <div class="rounded bg-white shadow-md">
            <div class="bg-grey-lighter border-b border-grey rounded-t py-2 px-4 font-semibold">{{ __('tasks.create') }}</div>

            <div class="pt-2 pb-4 px-4">
            	<create-task-form target="{{ route('task_lists.tasks.store', $task_list) }}"></create-task-form>
            </div>
        </div>
    </div>
</div>
@endsection
