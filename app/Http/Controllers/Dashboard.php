<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

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
                    $moment = $task->incompleted_scheduled_tasks->first()->scheduled_at;
                    if ($moment->isToday()) {
                        return '1-today';
                    }
                    if ($moment->isCurrentWeek()) {
                        if ($moment->isFuture()) {
                            return '5-future';
                        }
                        return '2-this_week';
                    }
                    if ($moment->isLastWeek()) {
                        return '3-last_week';
                    }
                    if ($moment->isPast()) {
                        return '4-older';
                    }
                    return '6-future';
                },
            ])
            ;
        return view('dashboard', compact('tabs', 'tasks'));
    }
}
