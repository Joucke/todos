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
		// provided we have a task that was scheduled
		$task = factory(Task::class)->create(['task_list_id' => $this->list->id]);
		$scheduled = $task->schedule();

		// when we post to /tasks/1/completed_tasks
		$this->actingAs($this->user)
			->withoutExceptionHandling()
			->post('/tasks/'.$task->id.'/completed_tasks')
			->assertRedirect('/task_lists/'.$task->task_list_id.'/tasks');

		// then the scheduled_task is completed
		$this->assertEquals(now()->format('Y-m-d H:i:s'), $scheduled->fresh()->completed_at->format('Y-m-d H:i:s'));
	}

	/** @test */
	public function an_unscheduled_task_can_be_completed()
	{
		// provided we have a task

		// when we post to /tasks/1/completed_tasks

		// then a scheduled task is created with interval 0
		// and the scheduled task is completed
	}

	/** @test */
	public function completing_a_task_schedules_the_task_again()
	{
		// provided we have a task

		// when we post to /tasks/1/completed_tasks

		// then the scheduled_task is completed
		// then an incompleted scheduled_task is created
	}

	/** @test */
	public function it_lists_completed_tasks()
	{
	    $this->markTestIncomplete();
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
