<?php

namespace Tests\Unit;

use App\Group;
use App\Invitation;
use App\Task;
use App\TaskList;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupTest extends TestCase
{
	/** @test */
	public function these_fields_are_fillable()
	{
		$group = new Group(['title' => 'foobar']);

		$this->assertEquals('foobar', $group->title);
	}

	/** @test */
	public function it_has_multiple_users()
	{
		$john = factory(User::class)->create();
		$jane = factory(User::class)->create();

		$group = factory(Group::class)->create();

		$group->users()->attach($john);
		$group->users()->attach($jane);

		tap($group->users->pluck('id'), function($users) use ($john, $jane) {
			$this->assertContains($john->id, $users);
			$this->assertContains($jane->id, $users);
		});
	}

	/** @test */
	public function it_has_an_owner()
	{
		$john = factory(User::class)->create();
		$group = factory(Group::class)->create();

		$group->users()->attach($john);
		$group->owner_id = $john->id;
		$group->save();

		$this->assertTrue($group->fresh()->owner->is($john));
	}

	/** @test */
	public function it_can_have_many_task_lists()
	{
		$group = factory(Group::class)->create();

		$holidays = factory(TaskList::class)->create([
			'group_id' => $group->id,
		]);
		$home = factory(TaskList::class)->create([
			'group_id' => $group->id,
		]);

		$this->assertCount(2, $group->fresh()->task_lists);
	}

	/** @test */
	public function it_can_have_many_tasks_through_task_lists()
	{
		$group = factory(Group::class)->create();

		$holidays = factory(TaskList::class)->create([
			'group_id' => $group->id,
		]);
		$home = factory(TaskList::class)->create([
			'group_id' => $group->id,
		]);
		$holidays->tasks()->create(factory(Task::class)->raw());
		$holidays->tasks()->create(factory(Task::class)->raw());
		$home->tasks()->create(factory(Task::class)->raw());
		$home->tasks()->create(factory(Task::class)->raw());

		$this->assertCount(4, $group->fresh()->tasks);
	}

	/** @test */
	public function it_can_have_many_invitations()
	{
		$group = factory(Group::class)->create();
		$invitation = factory(Invitation::class)->create([
			'group_id' => $group->id,
		]);

		$this->assertTrue($group->fresh()->invitations->contains('id', $invitation->id));
	}
}
