<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with user's tasks grouped by timing and groups
     */
    public function __invoke(Request $request)
    {
        // Get user's groups for tabs
        $tabs = auth()->user()->groups()->with('taskLists.tasks')->get();

        // Get tasks from user's groups with pending scheduled tasks
        $tasks = Task::with(['scheduledTasks', 'taskList.group'])
            ->whereHas('taskList.group.members', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->whereHas('scheduledTasks', function ($query) {
                $query->whereNull('completed_at'); // Only incomplete scheduled tasks
            })
            ->get()
            ->groupBy([
                function ($task) {
                    return $task->taskList->group_id;
                },
                function ($task) {
                    $scheduledTask = $task->scheduledTasks->where('completed_at', null)->first();
                    if (!$scheduledTask) {
                        return '6-future';
                    }

                    $moment = $scheduledTask->scheduled_at;

                    if ($moment->isToday()) {
                        return '1-today';
                    }
                    if ($moment->isCurrentWeek()) {
                        if ($moment->isFuture()) {
                            return '5-upcoming_week';
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
            ->map(function ($dates) {
                return $dates->sortKeys();
            });

        return view('dashboard', compact('tabs', 'tasks'));
    }
}
