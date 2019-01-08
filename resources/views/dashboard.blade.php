@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @foreach ($groups['scheduled'] as $group)
                        <div>
                            <h2>{{ $group->title }}</h2>
                            @foreach ($group->tasks as $task)
                                @if ($task->incompleted_scheduled_tasks->count())
                                    <div>
                                        <h3>{{ $task->title }}</h3>
                                        <p>{{ $task->task_list->title }}</p>
                                        <p>{{ __('Scheduled for') }}: {{ $task->incompleted_scheduled_tasks->first()->scheduled_at->diffForHumans() }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach

                    @foreach ($groups['unscheduled'] as $group)
                        <div>
                            <h2>{{ $group->title }}</h2>
                            @foreach ($group->tasks as $task)
                                @if (!$task->incompleted_scheduled_tasks->count())
                                    <div>
                                        <h3>{{ $task->title }}</h3>
                                        <p>{{ $task->task_list->title }}</p>
                                        <p>{{ __('Interval') }}: {{ $task->interval }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
