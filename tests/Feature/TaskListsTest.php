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

		$listCount = TaskList::count();
		$this->actingAs($jane)
			->post('/groups/'.$group->id.'/task_lists', [
				'title' => 'foobar',
			])
			->assertRedirect('/groups/'.$group->id);
		$this->assertEquals($listCount + 1, TaskList::count());

		$listCount = TaskList::count();
		$this->actingAs($this->user)
			->post('/groups/'.$group->id.'/task_lists', [
				'title' => 'foobar',
			])
			->assertRedirect('/groups/'.$group->id);
		$this->assertEquals($listCount + 1, TaskList::count());
	}

	/** @test */
	public function it_displays_a_flash_message_after_creating_a_group()
	{
		$group = $this->createGroup($this->user);

		$response = $this->actingAs($this->user)
			->post('/groups/'.$group->id.'/task_lists', [
				'title' => 'foobar',
			])
			->assertSessionHas('status', __('task_lists.statuses.created'));
	}

	/** @test */
	public function it_cannot_be_created_by_non_group_members()
	{
		$group = $this->createGroup($this->user);
		$jane = factory(User::class)->create();

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
	public function it_shows_the_task_frequency_for_each_task_on_a_list()
	{
	    $this->markTestIncomplete('Display with task frequency');
	}

	/** @test */
	public function it_shows_recently_completed_tasks_on_a_list()
	{
	    $this->markTestIncomplete('Display with completion date/time');
	}

	/** @test */
	public function it_can_be_updated_by_the_group_owner()
	{
		$group = $this->createGroup($this->user);
		$list = factory(TaskList::class)->create([
			'group_id' => $group->id,
			'title' => 'barbaz',
		]);

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
	public function it_cannot_be_updated_by_group_members()
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
	}

	/** @test */
	public function it_displays_a_flash_message_after_updating_a_group()
	{
		$group = $this->createGroup($this->user);
		$list = factory(TaskList::class)->create([
			'group_id' => $group->id,
			'title' => 'barbaz',
		]);

		$response = $this->actingAs($this->user)
			->patch('/task_lists/'.$list->id, [
				'title' => 'foobar',
			])
			->assertSessionHas('status', __('task_lists.statuses.updated'));
	}

	/** @test */
	public function it_can_be_sorted_by_group_owners()
	{
		$group = $this->createGroup($this->user);

		[$first, $second] = factory(TaskList::class, 2)->create([
			'group_id' => $group->id,
		]);
		$first->tasks()->create(factory(Task::class)->raw());
		$first->load('tasks');

		$this->actingAs($this->user)
			->patch('/groups/'.$group->id.'/task_lists', [
				'task_list_order' => [
					$second->id => ['sort_order' => 1],
					$first->id => ['sort_order' => 2],
				],
			])
			->assertJson([
				'status' => 200,
				'items' => [
					$second->toArray(),
					$first->toArray(),
				],
			]);
	}

	/** @test */
	public function it_cannot_be_sorted_by_group_members()
	{
		$john = factory(User::class)->create();
		$group = $this->createGroup($this->user, [], $john);

		[$first, $second] = factory(TaskList::class, 2)->create([
			'group_id' => $group->id,
		]);

		$this->actingAs($john)
			->patch('/groups/'.$group->id.'/task_lists', [
				'task_list_order' => [
					$second->id => ['sort_order' => 1],
					$first->id => ['sort_order' => 2],
				],
			])
			->assertForbidden();
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

		$listCount = TaskList::count();
		$this->actingAs($this->user)
			->delete('/task_lists/'.$list->id)
			->assertRedirect('/groups/'.$group->id);
		$this->assertEquals($listCount - 1, TaskList::count());
	}

	/** @test */
	public function it_cannot_be_deleted_by_group_members()
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
	}

	/** @test */
	public function it_displays_a_flash_message_after_deleting_a_group()
	{
		$group = $this->createGroup($this->user);
		$list = factory(TaskList::class)->create([
			'group_id' => $group->id,
			'title' => 'barbaz',
		]);

		$response = $this->actingAs($this->user)
			->delete('/task_lists/'.$list->id)
			->assertSessionHas('status', __('task_lists.statuses.deleted'));
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
