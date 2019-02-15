<?php

namespace App\Http\Controllers;

use App\ScheduledTask;
use App\Task;
use App\TaskList;
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
        $my_groups = collect([
            'scheduled' => auth()->user()->groups()
                ->has('tasks.incompleted_scheduled_tasks')
                ->with(['tasks.incompleted_scheduled_tasks', 'tasks.task_list'])
                ->get(),
            'unscheduled' => auth()->user()->groups()
                ->whereHas('tasks', function ($query) {
                    $query->doesntHave('scheduled_tasks');
                })
                ->with(['tasks.incompleted_scheduled_tasks', 'tasks.task_list'])
                ->get(),
        ]);
        return view('dashboard', compact('my_groups'));
    }
}
