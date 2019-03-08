<?php

namespace App\Listeners;

use App\Events\TaskSaved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ScheduleTask
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TaskSaved  $event
     * @return void
     */
    public function handle(TaskSaved $event)
    {
        if ($event->task->scheduled_tasks->count() < 1) {
            $event->task->schedule();
        }
    }
}
