<?php

namespace Tests\Unit;

use App\ScheduledTask;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ScheduledTaskTest extends TestCase
{
	/** @test */
	public function these_fields_are_fillable()
	{
	    $scheduled = new ScheduledTask([
	    	'task_id' => 1,
	    	'scheduled_at' => $when = now(),
	    ]);

	    $this->assertEquals(1, $scheduled->task_id);
	    $this->assertEquals($when, $scheduled->scheduled_at);
	}

	/** @test */
	public function it_belongs_to_a_task()
	{
	    $task = factory(Task::class)->create();
	    $scheduled = factory(ScheduledTask::class)->create(['task_id' => $task->id]);

	    $this->assertTrue($scheduled->fresh()->task->is($task));
	}

	/** @test */
	public function it_can_be_completed()
	{
	    $task = factory(Task::class)->create();
	    $scheduled = factory(ScheduledTask::class)->create(['task_id' => $task->id]);

	    $scheduled->complete();

	    $this->assertNotNull($scheduled->fresh()->completed_at);
	}

	/** @test */
	public function it_scopes_on_incomplete()
	{
	    $task = factory(Task::class)->create();
	    $scheduled = factory(ScheduledTask::class)->create(['task_id' => $task->id]);

	    $this->assertTrue(ScheduledTask::incomplete()->get()->contains('id', $scheduled->id));
	    
	    $scheduled->complete();

	    $this->assertFalse(ScheduledTask::incomplete()->get()->contains('id', $scheduled->id));
	}
}
