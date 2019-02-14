<?php

namespace Tests\Feature;

use App\Group;
use App\Task;
use App\TaskList;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompletedTasksTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();

		$this->user = factory(User::class)->create();
		$group = $this->createGroup($this->user);
		$this->list = $group->task_lists()->create(factory(TaskList::class)->raw());
	}

	/** @test */
	public function a_scheduled_task_can_be_completed()
	{
		$task = factory(Task::class)->create(['task_list_id' => $this->list->id]);
		$scheduled = $task->schedule();

		$this->actingAs($this->user)
			->withoutExceptionHandling()
			->post('/tasks/'.$task->id.'/completed_tasks')
			->assertRedirect('/task_lists/'.$task->task_list_id);

		$this->assertEquals(now()->format('Y-m-d H:i:s'), $scheduled->fresh()->completed_at->format('Y-m-d H:i:s'));
	}

	/** @test */
	public function an_unscheduled_task_can_be_completed()
	{
		$task = factory(Task::class)->create(['task_list_id' => $this->list->id]);
		$this->assertCount(0, $task->scheduled_tasks);

		$this->actingAs($this->user)
			->withoutExceptionHandling()
			->post('/tasks/'.$task->id.'/completed_tasks')
			->assertRedirect('/task_lists/'.$task->task_list_id);

		$this->assertCount(1, $task->fresh()->scheduled_tasks()->completed()->get());

		$this->assertEquals(now()->format('Y-m-d H:i:s'), $task->fresh()->scheduled_tasks->first()->completed_at->format('Y-m-d H:i:s'));
	}

	/** @test */
	public function completing_a_task_schedules_the_task_again()
	{
		$task = factory(Task::class)->create(['task_list_id' => $this->list->id]);
		$scheduled = $task->schedule();
		$this->assertCount(0, $task->scheduled_tasks()->completed()->get());
		$this->assertCount(1, $task->scheduled_tasks()->incompleted()->get());
		$this->assertTrue($task->scheduled_tasks()->incompleted()->get()->contains('id', $scheduled->id));

		$this->actingAs($this->user)
			->withoutExceptionHandling()
			->post('/tasks/'.$task->id.'/completed_tasks')
			->assertRedirect('/task_lists/'.$task->task_list_id);

		$this->assertEquals(now()->format('Y-m-d H:i:s'), $scheduled->fresh()->completed_at->format('Y-m-d H:i:s'));
		$this->assertCount(1, $task->scheduled_tasks()->completed()->get());
		$this->assertTrue($task->scheduled_tasks()->completed()->get()->contains('id', $scheduled->id));

		$this->assertCount(1, $task->scheduled_tasks()->incompleted()->get());
		$this->assertFalse($task->scheduled_tasks()->incompleted()->get()->contains('id', $scheduled->id));

	}

	protected function createGroup(User $owner, array $overrides = [], User $member = null)
	{
		if (!$member) {
			$member = $owner;
		}
		$groupData = factory(Group::class)->raw($overrides);
		$group = $owner->owned_groups()->create($groupData);
		$group->users()->attach($member);

		return $group;
	}
}
