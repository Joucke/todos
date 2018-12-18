<?php

namespace Tests\Unit;

use App\Group;
use App\Task;
use App\TaskList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskListTest extends TestCase
{
	/** @test */
	public function these_fields_are_fillable()
	{
	    $list = new TaskList([
	    	'title' => 'foobar',
	    	'group_id' => 1,
	    ]);

	    $this->assertEquals('foobar', $list->title);
	    $this->assertEquals(1, $list->group_id);
	}

	/** @test */
	public function it_belongs_to_a_group()
	{
		$group = factory(Group::class)->create();
	    $list = factory(TaskList::class)->create([
	    	'group_id' => $group->id,
	    ]);

	    $this->assertTrue($list->group->is($group));
	}

	/** @test */
	public function it_has_many_tasks()
	{
	    $list = factory(TaskList::class)->create();
	    $goToStore = factory(Task::class)->create(['task_list_id' => $list->id]);
	    $cleanHouse = factory(Task::class)->create(['task_list_id' => $list->id]);

	    tap($list->fresh(), function ($list) use ($goToStore, $cleanHouse) {
		    $this->assertCount(2, $list->tasks);
	    	$this->assertContains($goToStore->id, $list->tasks->pluck('id'));
	    	$this->assertContains($cleanHouse->id, $list->tasks->pluck('id'));
	    });
	}
}
