@extends('layouts.app')

@section('content')
<div class="flex flex-wrap justify-around">
    <div class="w-full md:w-1/2 p-2">
        <div class="rounded bg-white shadow-md">
            <div class="bg-grey-lighter border-b border-grey rounded-t py-2 px-4 font-semibold">Create a Task</div>

            <div class="pt-2 pb-4 px-4">
            	<form action="{{ route('task_lists.tasks.store', $task_list) }}" method="POST">
            		{{ csrf_field() }}
                    <p class="flex items-baseline py-2">
                		<label class="w-1/4" for="title">Title:</label>
                        <input class="bg-blue-lightest border rounded border-blue-lighter py-2 px-2" placeholder="title" type="text" name="title" value="{{ old('title') }}">
                    </p>
                    <p class="flex items-baseline py-2">
                        <label class="w-1/4" for="interval">Interval:</label>
                		<input class="bg-blue-lightest border rounded border-blue-lighter py-2 px-2" type="number" name="interval" value="{{ old('interval') }}">
                    </p>
            		<p class="flex items-baseline py-2">
                        <label class="w-1/4"></label>
                        <input class="bg-blue hover:bg-blue-dark text-white no-underline py-2 px-4 rounded" type="submit" value="Add Task">
            	</form>
            </div>
        </div>
    </div>
</div>
@endsection
