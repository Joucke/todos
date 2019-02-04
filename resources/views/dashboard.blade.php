@extends('layouts.app')

@section('content')

    @foreach ($groups['scheduled'] as $group)
        {{-- TODO: design tabs (on the side on lg:xxx) --}}
        <div>
            <h1>{{ $group->title }}</h1>
            <div class="flex flex-wrap md:-mx-2">
                @foreach ($group->tasks as $task)
                    @if ($task->incompleted_scheduled_tasks->count())
                        <clickable-card
                            :task-data="{{ $task }}"
                            click-action="{{ route('tasks.completed_tasks.store', $task) }}">
                        </clickable-card>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach


    @foreach ($groups['unscheduled'] as $group)
        <div>
            <h1>{{ $group->title }}</h1>
            <div class="flex flex-wrap md:-mx-2">
                @foreach ($group->tasks as $task)
                    @if (!$task->incompleted_scheduled_tasks->count())
                        <clickable-card
                            :task-data="{{ $task }}"
                            click-action="{{ route('tasks.completed_tasks.store', $task) }}">
                        </clickable-card>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach

@endsection
