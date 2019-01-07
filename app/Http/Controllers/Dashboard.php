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
        $groups = auth()->user()->groups()
            ->has('task_lists.tasks.incompleted_scheduled_tasks')
            ->with('task_lists.tasks.incompleted_scheduled_tasks')
            ->get();
        return view('dashboard', compact('groups'));
    }
}
