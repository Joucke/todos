<?php

namespace App\Http\Controllers;

use App\ScheduledTask;
use App\Task;
use App\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // order by day, task_list, then time
        $tabs = auth()->user()->groups()->with('tasks.incompleted_scheduled_tasks', 'tasks.task_list')->get();
        $scheduled_tasks = ScheduledTask::incompleted()
            ->whereHas('task.task_list.group', function ($query) {
                $query->whereIn('groups.id', auth()->user()->groups->pluck('id'));
            })
            ->with('task.task_list')
            ->orderBy('scheduled_at', 'asc')
            ->get()
            ->groupBy([
                function ($sTask) {
                    return $sTask->task->task_list->group_id;
                },
                function ($sTask) {
                    return $sTask->scheduled_at->toDateString();
                },
            ]);
        $tasks = Task::with(['incompleted_scheduled_tasks', 'task_list'])
            ->whereHas('task_list.group', function ($query) {
                $query->whereIn('groups.id', auth()->user()->groups->pluck('id'));
            })
            ->has('incompleted_scheduled_tasks')
            ->get()
            ->groupBy([
                function ($task) {
                    return $task->task_list->group_id;
                },
                function ($task) {
                    return $task->incompleted_scheduled_tasks->first()->scheduled_at->toDateString();
                },
            ])
            ->map(function ($dates) {
                return $dates->map(function ($items) {
                    return $items->sortBy(function ($task) {
                        // TODO: get a sort_order for task_lists
                        return $task->task_list_id . '-' . $task->incompleted_scheduled_tasks->first()->scheduled_at->getTimestamp();
                    })->values();
                })->sortKeys();
            })
            ;
        return view('dashboard', compact('tabs', 'tasks'));
    }
}
