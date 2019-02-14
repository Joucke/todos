@extends('layouts.app')

@section('content')
<header class="flex justify-between items-center">
	<h1 class="page-header">{{ __('tasks.edit') }}</h1>
</header>
<div class="py-4">
    <div class="card-container">
        <div class="card-padding-full mb-4">
            <task-form
                class="card bg-white"
                :task-data="{{ $task }}"
                action="{{ route('task_lists.tasks.update', compact('task_list', 'task')) }}"
                method="patch"
                >
            </task-form>
        </div>
    </div>
</div>
@endsection
