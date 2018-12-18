<?php

namespace Tests\Feature;

use App\Group;
use App\Task;
use App\TaskList;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TasksTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();

		$this->user = factory(User::class)->create();
		$group = $this->createGroup($this->user);
		$this->list = $group->task_lists()->create(factory(TaskList::class)->raw());
	}

	/** @test */
	public function guests_cannot_see_tasks()
	{
		$this->get('/task_lists/'.$this->list->id.'/tasks')
			->assertRedirect('login');

		$this->actingAs($this->user)
			->get('/task_lists/'.$this->list->id.'/tasks')
			->assertOk();
	}

	/** @test */
	public function it_lists_all_tasks_for_current_list()
	{
		$this->actingAs($this->user)
			->get('/task_lists/'.$this->list->id.'/tasks')
			->assertViewIs('tasks.index')
			->assertViewHas('task_list', function ($task_list) {
				return $task_list->is($this->list);
			});
	}

	/** @test */
	public function it_cannot_be_created_by_non_members()
	{
		$jane = factory(User::class)->create();

		$this->actingAs($jane)
			->get('/task_lists/'.$this->list->id.'/tasks/create')
			->assertForbidden();

		$taskCount = Task::count();
		$this->actingAs($jane)
			->post('/task_lists/'.$this->list->id.'/tasks', [
				'title' => 'foobar',
				'interval' => 1,
			])
			->assertForbidden();
		$this->assertEquals($taskCount, Task::count());

	}

	/** @test */
	public function it_can_be_created_by_any_group_member()
	{
		$jane = factory(User::class)->create();
		$this->list->group->users()->attach($jane);

		$this->actingAs($jane)
			->get('/task_lists/'.$this->list->id.'/tasks/create')
			->assertOk();

		$taskCount = Task::count();
		$this->actingAs($jane)
			->post('/task_lists/'.$this->list->id.'/tasks', [
				'title' => 'foobar',
				'interval' => 1,
			])
			->assertRedirect('/task_lists/'.$this->list->id.'/tasks');
		$this->assertEquals($taskCount + 1, Task::count());

		$this->actingAs($this->user)
			->get('/task_lists/'.$this->list->id.'/tasks/create')
			->assertOk()
			->assertViewIs('tasks.create')
			->assertViewHas('task_list', function ($task_list) {
				return $task_list->is($this->list);
			});

		$this->actingAs($this->user)
			->post('/task_lists/'.$this->list->id.'/tasks', [
				'title' => 'foobar',
				'interval' => 1,
			])
			->assertRedirect('/task_lists/'.$this->list->id.'/tasks');
		$this->assertEquals($taskCount + 2, Task::count());
	}

	/** @test */
	public function it_cannot_be_viewed_by_non_members()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw());
		$jane = factory(User::class)->create(['name' => 'Jane Doe']);

		$this->actingAs($jane)
			->get('/task_lists/'.$this->list->id.'/tasks/'.$task->id)
			->assertForbidden();
	}

	/** @test */
	public function it_can_be_viewed_by_group_members()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw());

		$this->actingAs($this->user)
			->get('/task_lists/'.$this->list->id.'/tasks/'.$task->id)
			->assertOk()
			->assertViewIs('tasks.show')
			->assertViewHas('task', function ($t) use ($task) {
				return $t->is($task);
			});
	}

	/** @test */
	public function it_cannot_be_updated_by_non_members()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw());
		$jane = factory(User::class)->create(['name' => 'Jane Doe']);

		$this->actingAs($jane)
			->get('/task_lists/'.$this->list->id.'/tasks/'.$task->id.'/edit')
			->assertForbidden();

		$this->actingAs($jane)
			->patch('/task_lists/'.$this->list->id.'/tasks/'.$task->id, [
				'title' => 'foobar',
				'interval' => 1,
			])
			->assertForbidden();
	}

	/** @test */
	public function it_can_be_updated_by_group_members()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw());
		$jane = factory(User::class)->create(['name' => 'Jane Doe']);
		$this->list->group->users()->attach($jane);

		$this->actingAs($jane)
			->get('/task_lists/'.$this->list->id.'/tasks/'.$task->id.'/edit')
			->assertOk();

		$this->actingAs($jane)
			->patch('/task_lists/'.$this->list->id.'/tasks/'.$task->id, [
				'title' => 'foobar',
				'interval' => 1,
			])
			->assertRedirect('/task_lists/'.$this->list->id.'/tasks');
		tap($task->fresh(), function ($task) {
			$this->assertEquals('foobar', $task->title);
			$this->assertEquals(1, $task->interval);
		});

		$this->actingAs($this->user)
			->get('/task_lists/'.$this->list->id.'/tasks/'.$task->id.'/edit')
			->assertOk()
			->assertViewIs('tasks.edit')
			->assertViewHas('task_list', function ($task_list) {
				return $task_list->is($this->list);
			})
			->assertViewHas('task', function ($t) use ($task) {
				return $t->is($task);
			});

		$this->actingAs($this->user)
			->patch('/task_lists/'.$this->list->id.'/tasks/'.$task->id, [
				'title' => 'barbaz',
				'interval' => 4,
			])
			->assertRedirect('/task_lists/'.$this->list->id.'/tasks');
		tap($task->fresh(), function ($task) {
			$this->assertEquals('barbaz', $task->title);
			$this->assertEquals(4, $task->interval);
		});
	}

	/** @test */
	public function it_cannot_be_deleted_by_non_members()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw());
		$jane = factory(User::class)->create(['name' => 'Jane Doe']);

		$taskCount = Task::count();
		$this->actingAs($jane)
			->delete('/task_lists/'.$this->list->id.'/tasks/'.$task->id)
			->assertForbidden();
		$this->assertEquals($taskCount, Task::count());
	}

	/** @test */
	public function it_can_be_deleted_by_group_members()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw());
		$jane = factory(User::class)->create(['name' => 'Jane Doe']);
		$this->list->group->users()->attach($jane);

		$taskCount = Task::count();
		$this->actingAs($jane)
			->delete('/task_lists/'.$this->list->id.'/tasks/'.$task->id)
			->assertRedirect('/task_lists/'.$this->list->id.'/tasks');
		$this->assertEquals($taskCount - 1, Task::count());

		$task = $this->list->tasks()->create(factory(Task::class)->raw());
		
		$taskCount = Task::count();
		$this->actingAs($this->user)
			->delete('/task_lists/'.$this->list->id.'/tasks/'.$task->id)
			->assertRedirect('/task_lists/'.$this->list->id.'/tasks');
		$this->assertEquals($taskCount - 1, Task::count());
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
