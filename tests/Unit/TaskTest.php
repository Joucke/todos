<?php

namespace Tests\Unit;

use App\ScheduledTask;
use App\Task;
use App\TaskList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
	/** @test */
	public function these_fields_are_fillable()
	{
	    $task = new Task([
	    	'title' => 'go to the store',
	    	'task_list_id' => 1,
	    	'interval' => 1,
	    ]);

	    $this->assertEquals('go to the store', $task->title);
	    $this->assertEquals(1, $task->task_list_id);
	    $this->assertEquals(1, $task->interval);
	}

	/** @test */
	public function it_belongs_to_a_task_list()
	{
	    $list = factory(TaskList::class)->create();

	    $task = factory(Task::class)->create(['task_list_id' => $list->id]);

	    $this->assertTrue($task->task_list->is($list));
	}

	/** @test */
	public function it_can_schedule_a_task()
	{
		$interval = 1;
	    $task = factory(Task::class)->create(['interval' => $interval]);
	    $scheduled = $task->schedule();

	    $this->assertInstanceOf(ScheduledTask::class, $scheduled);
	    $this->assertEquals(now()->addDays($interval)->format("Y-m-d H:i:s"), $scheduled->scheduled_at->format("Y-m-d H:i:s"));
	}

	/** @test */
	public function it_can_schedule_a_task_on_an_interval()
	{
		$interval = 1;
	    $task = factory(Task::class)->create(['interval' => $interval]);
	    $scheduled = $task->schedule(2);

	    $this->assertInstanceOf(ScheduledTask::class, $scheduled);
	    $this->assertEquals(now()->addDays(2)->format("Y-m-d H:i:s"), $scheduled->scheduled_at->format("Y-m-d H:i:s"));
	}

	/** @test */
	public function it_has_many_scheduled_tasks()
	{
	    $task = factory(Task::class)->create();
	    $today = factory(ScheduledTask::class)->create(['task_id' => $task->id, 'scheduled_at' => now()]);
	    $tomorrow = factory(ScheduledTask::class)->create(['task_id' => $task->id, 'scheduled_at' => now()->addDay()]);

	    $this->assertTrue($task->scheduled_tasks->contains('id', $today->id));
	    $this->assertTrue($task->scheduled_tasks->contains('id', $tomorrow->id));
	}

	/** @test */
	public function it_has_many_incompleted_scheduled_tasks()
	{
	    $task = factory(Task::class)->create();
	    $completed = factory(ScheduledTask::class)->create(['task_id' => $task->id, 'scheduled_at' => now()]);
	    $incompleted = factory(ScheduledTask::class)->create(['task_id' => $task->id, 'scheduled_at' => now()->addDay()]);
	    $completed->complete();

	    tap($task->fresh(), function ($task) use ($completed, $incompleted) {
		    $this->assertFalse($task->incompleted_scheduled_tasks->contains('id', $completed->id));
		    $this->assertTrue($task->incompleted_scheduled_tasks->contains('id', $incompleted->id));
	    });
	}
}
