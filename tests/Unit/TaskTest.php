<?php

namespace Tests\Unit;

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
}
