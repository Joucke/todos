<?php

namespace Tests\Feature;

use App\Group;
use App\Task;
use App\TaskList;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskListsTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();

		$this->user = factory(User::class)->create();
	}

	/** @test */
	public function it_can_be_created_by_any_group_member()
	{
		$group = $this->createGroup($this->user);
		$jane = factory(User::class)->create();
		$group->users()->attach($jane);

		$this->actingAs($jane)
			->get('/groups/'.$group->id.'/task_lists/create')
			->assertOk();

		$this->actingAs($jane)
			->post('/groups/'.$group->id.'/task_lists', [
				'title' => 'foobar',
			])
			->assertRedirect('/groups/'.$group->id);

		$this->actingAs($this->user)
			->get('/groups/'.$group->id.'/task_lists/create')
			->assertOk()
			->assertViewIs('task_lists.create')
			->assertViewHas('group', $group);

		$listCount = TaskList::count();
		$this->actingAs($this->user)
			->post('/groups/'.$group->id.'/task_lists', [
				'title' => 'foobar',
			])
			->assertRedirect('/groups/'.$group->id);
		$this->assertEquals($listCount + 1, TaskList::count());
	}

	/** @test */
	public function it_cannot_be_created_by_non_group_members()
	{
		$group = $this->createGroup($this->user);
		$jane = factory(User::class)->create();

		$this->actingAs($jane)
			->get('/groups/'.$group->id.'/task_lists/create')
			->assertForbidden();

		$this->actingAs($jane)
			->post('/groups/'.$group->id.'/task_lists', [
				'title' => 'foobar',
			])
			->assertForbidden();
	}

	/** @test */
	public function it_can_be_viewed_by_group_members()
	{
		$group = $this->createGroup($this->user);
		$jane = factory(User::class)->create();
		$group->users()->attach($jane);
		$list = factory(TaskList::class)->create(['group_id' => $group->id]);

		$this->actingAs($jane)
			->get('/task_lists/'.$list->id)
			->assertOk()
			->assertViewIs('task_lists.show')
			->assertViewHas('task_list', function ($task_list) use ($list) {
				return $task_list->is($list);
			});
	}

	/** @test */
	public function it_shows_breadcrumbs()
	{
	    $this->markTestIncomplete('Dusk test?');
	}

	/** @test */
	public function it_shows_all_tasks_for_a_task_list()
	{
		$group = $this->createGroup($this->user);
		$jane = factory(User::class)->create();
		$group->users()->attach($jane);
		$list = factory(TaskList::class)->create(['group_id' => $group->id]);
		$task = factory(Task::class)->create(['task_list_id' => $list->id]);

		$this->actingAs($jane)
			->get('/task_lists/'.$list->id)
			->assertOk()
			->assertViewIs('task_lists.show')
			->assertViewHas('task_list', function ($task_list) use ($list) {
				return $task_list->is($list);
			})
			->assertViewHas('task_list', function ($task_list) use ($task) {
				return $task_list->tasks->contains('id', $task->id);
			});
	}

	/** @test */
	public function it_shows_task_data_for_each_task_on_a_list()
	{
	    $this->markTestIncomplete();
	}

	/** @test */
	public function it_can_be_updated_by_the_group_owner()
	{
		$group = $this->createGroup($this->user);
		$jane = factory(User::class)->create();
		$group->users()->attach($jane);
		$list = factory(TaskList::class)->create([
			'group_id' => $group->id,
			'title' => 'barbaz',
		]);

		$this->actingAs($jane)
			->get('/task_lists/'.$list->id.'/edit')
			->assertForbidden();

		$this->actingAs($jane)
			->patch('/task_lists/'.$list->id, [
				'title' => 'foobar',
				'group_id' => $group->id,
			])
			->assertForbidden();

		$this->actingAs($this->user)
			->get('/task_lists/'.$list->id.'/edit')
			->assertOk()
			->assertViewIs('task_lists.edit')
			->assertViewHas('groups', function ($groups) {
				return $groups->pluck('id') == $this->user->groups->pluck('id');
			});

		$listCount = TaskList::count();
		$this->actingAs($this->user)
			->patch('/task_lists/'.$list->id, [
				'title' => 'foobar',
			])
			->assertRedirect('/groups/'.$group->id);
		$this->assertEquals($listCount, TaskList::count());
		$this->assertEquals('foobar', $list->fresh()->title);
	}

	/** @test */
	public function it_can_be_deleted_by_the_group_owner()
	{
		$group = $this->createGroup($this->user);
		$jane = factory(User::class)->create();
		$group->users()->attach($jane);
		$list = factory(TaskList::class)->create([
			'group_id' => $group->id,
			'title' => 'barbaz',
		]);

		$this->actingAs($jane)
			->delete('/task_lists/'.$list->id)
			->assertForbidden();

		$listCount = TaskList::count();
		$this->actingAs($this->user)
			->delete('/task_lists/'.$list->id)
			->assertRedirect('/groups/'.$group->id);
		$this->assertEquals($listCount - 1, TaskList::count());
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
