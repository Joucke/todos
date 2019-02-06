@extends('layouts.app')

@section('content')
<header class="flex justify-between items-center">
    <h1 class="page-header">{{ __('task_lists.edit') }}</h1>
</header>
<div class="py-4">
    <div class="card-container">
        <div class="card-padding mb-4">
			<form action="{{ route('task_lists.update', $task_list) }}" method="POST" class="card bg-white">
				@csrf
                @method('PATCH')
                <div class="card-body flex flex-col">
                    <label class="mb-1" for="title">{{ __('task_lists.title') }}:</label>
                    <input
                        class="border rounded py-2 px-2"
                        placeholder="{{ __('task_lists.placeholders.title') }}"
                        type="text"
                        name="title"
                        value="{{ old('title', $task_list->title) }}">
                </div>
                <div class="card-footer">
                    <input class="button button-blue w-full rounded-t-none" type="submit" value="{{ __('task_lists.update') }}">
                </div>
			</form>
		</div>
	</div>
</div>
@endsection
