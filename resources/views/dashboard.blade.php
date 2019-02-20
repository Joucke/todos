@extends('layouts.app')

@section('content')
<tabbed-cards
    class="card-container"
    :tabs="{{ $tabs }}"
    :cards="{{ $tasks }}"
    >
</tabbed-cards>
{{--
    @foreach ($my_groups['scheduled'] as $group)
        <div>
            <h1>{{ $group->title }}</h1>
            <div class="card-container">
                @foreach ($group->tasks as $task)
                    @if ($task->incompleted_scheduled_tasks->count())
                        <task-card
                            :show-title="true"
                            :task-data="{{ $task }}"
                            :clickable="true"
                            >
                        </task-card>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach


    @foreach ($my_groups['unscheduled'] as $group)
        <div>
            <h1>{{ $group->title }}</h1>
            <div class="card-container">
                @foreach ($group->tasks as $task)
                    @if (!$task->incompleted_scheduled_tasks->count())
                        <task-card
                            :show-title="true"
                            :task-data="{{ $task }}"
                            :clickable="true"
                            >
                        </task-card>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
 --}}
@endsection
