@extends('layouts.app')

@section('content')
<header class="flex justify-between items-center">
    <h1 class="page-header">{{ __('tasks.create') }}</h1>
</header>
<div class="py-4">
    <div class="card-container">
        <div class="card-padding-full mb-4">
        	<task-form
                class="card bg-white"
                :task-data="{title:'',interval:1,data:{}}"
                action="{{ route('task_lists.tasks.store', $task_list) }}"
                >
            </task-form>
        </div>
    </div>
</div>
@endsection
