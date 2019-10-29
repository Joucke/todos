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
	public function setUp():void
	{
		parent::setUp();

		$this->user = factory(User::class)->create();
		$group = $this->createGroup($this->user);
		$this->list = $group->task_lists()->create(factory(TaskList::class)->raw());
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

		$this->actingAs($this->user)
			->get('/task_lists/'.$this->list->id.'/tasks/create')
			->assertOk()
			->assertViewIs('tasks.create')
			->assertViewHas('task_list', function ($task_list) {
				return $task_list->is($this->list);
			});

		$taskCount = Task::count();
		$this->actingAs($jane)
			->post('/task_lists/'.$this->list->id.'/tasks', [
				'title' => 'foobar',
				'interval' => 1,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id),
			]);
		$this->assertEquals($taskCount + 1, Task::count());

		$this->actingAs($this->user)
			->post('/task_lists/'.$this->list->id.'/tasks', [
				'title' => 'foobar',
				'interval' => 1,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id),
			]);
		$this->assertEquals($taskCount + 2, Task::count());
	}

	/** @test */
	public function a_created_task_can_be_repeated_daily()
	{
		$taskCount = Task::count();
		$this->actingAs($this->user)
			->post('/task_lists/'.$this->list->id.'/tasks', $attributes = [
				'title' => 'foobar',
				'interval' => 1,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id),
			]);
		$this->assertEquals($taskCount + 1, Task::count());

		$this->assertDatabaseHas('tasks', $attributes);
	}

	/** @test */
	public function a_created_task_can_be_repeated_every_other_day()
	{
		$taskCount = Task::count();
		$this->actingAs($this->user)
			->post('/task_lists/'.$this->list->id.'/tasks', $attributes = [
				'title' => 'foobar',
				'interval' => 2,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id),
			]);
		$this->assertEquals($taskCount + 1, Task::count());

		$this->assertDatabaseHas('tasks', $attributes);
	}

	/** @test */
	public function a_created_task_can_be_repeated_weekly()
	{
		$taskCount = Task::count();
		$this->actingAs($this->user)
			->post('/task_lists/'.$this->list->id.'/tasks', $attributes = [
				'title' => 'foobar',
				'interval' => 7,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id),
			]);
		$this->assertEquals($taskCount + 1, Task::count());

		$this->assertDatabaseHas('tasks', $attributes);
	}

	/** @test */
	public function a_created_task_can_be_repeated_every_other_week()
	{
		$taskCount = Task::count();
		$this->actingAs($this->user)
			->post('/task_lists/'.$this->list->id.'/tasks', $attributes = [
				'title' => 'foobar',
				'interval' => 14,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id),
			]);
		$this->assertEquals($taskCount + 1, Task::count());

		$this->assertDatabaseHas('tasks', $attributes);
	}

	/** @test */
	public function a_created_task_can_be_repeated_monthly()
	{
		$taskCount = Task::count();
		$this->actingAs($this->user)
			->post('/task_lists/'.$this->list->id.'/tasks', $attributes = [
				'title' => 'foobar',
				'interval' => 30,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id),
			]);
		$this->assertEquals($taskCount + 1, Task::count());

		$this->assertDatabaseHas('tasks', $attributes);
	}

	/** @test */
	public function a_created_task_can_be_repeated_every_other_month()
	{
		$taskCount = Task::count();
		$this->actingAs($this->user)
			->post('/task_lists/'.$this->list->id.'/tasks', $attributes = [
				'title' => 'foobar',
				'interval' => 60,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id),
			]);
		$this->assertEquals($taskCount + 1, Task::count());

		$this->assertDatabaseHas('tasks', $attributes);
	}

	/** @test */
	public function a_created_task_can_be_repeated_every_week_on_mondays_and_thursdays()
	{
		$taskCount = Task::count();
		$this->actingAs($this->user)
			->post('/task_lists/'.$this->list->id.'/tasks', $attributes = [
				'title' => 'foobar',
				'interval' => 7,
				'days' => [
					'mon' => true,
					'tue' => false,
					'wed' => false,
					'thu' => true,
					'fri' => false,
					'sat' => false,
					'sun' => false,
				],
				'data' => [
					'interval' => 77,
				],
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id),
			]);
		$this->assertEquals($taskCount + 1, Task::count());

		$task = Task::latest()->first();
		$this->assertEquals('foobar', $task->title);
		$this->assertEquals(7, $task->interval);
		$this->assertEquals(['interval' => 77], $task->data);
		$this->assertEquals([
			'mon' => true,
			'tue' => false,
			'wed' => false,
			'thu' => true,
			'fri' => false,
			'sat' => false,
			'sun' => false,
		], $task->days);
	}

	/** @test */
	public function a_created_task_can_be_repeated_every_4_weeks()
	{
		$taskCount = Task::count();
		$this->actingAs($this->user)
			->post('/task_lists/'.$this->list->id.'/tasks', $attributes = [
				'title' => 'foobar',
				'interval' => 28,
				'data' => [
					'interval' => 88,
					'weeks' => 4,
				],
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id),
			]);
		$this->assertEquals($taskCount + 1, Task::count());

		$task = Task::latest()->first();
		$this->assertEquals('foobar', $task->title);
		$this->assertEquals(28, $task->interval);
		$this->assertEquals(['interval' => 88, 'weeks' => 4], $task->data);
	}

	/** @test */
	public function a_created_task_can_be_repeated_every_4_months()
	{
		$taskCount = Task::count();
		$this->actingAs($this->user)
			->post('/task_lists/'.$this->list->id.'/tasks', $attributes = [
				'title' => 'foobar',
				'interval' => 120,
				'data' => [
					'interval' => 99,
					'months' => 4,
				],
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id),
			]);
		$this->assertEquals($taskCount + 1, Task::count());

		$task = Task::latest()->first();
		$this->assertEquals('foobar', $task->title);
		$this->assertEquals(120, $task->interval);
		$this->assertEquals(['interval' => 99, 'months' => 4], $task->data);
	}

	/** @test */
	public function a_created_task_can_be_repeated_between_dates()
	{
		$taskCount = Task::count();
		$this->actingAs($this->user)
			->post('/task_lists/'.$this->list->id.'/tasks', $attributes = [
				'title' => 'foobar',
				'interval' => 7,
				'starts_at' => '2010-01-01',
				'ends_at' => '2020-01-01',
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id),
			]);
		$this->assertEquals($taskCount + 1, Task::count());

		$task = Task::latest()->first();
		$this->assertEquals('foobar', $task->title);
		$this->assertEquals(7, $task->interval);
	    $this->assertEquals('2010-01-01', $task->starts_at->format('Y-m-d'));
	    $this->assertEquals('2020-01-01', $task->ends_at->format('Y-m-d'));
	}

	/** @test */
	public function a_created_task_can_be_optional()
	{
		$taskCount = Task::count();
		$this->actingAs($this->user)
			->post('/task_lists/'.$this->list->id.'/tasks', $attributes = [
				'title' => 'foobar',
				'interval' => 7,
				'optional' => true,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id),
			]);
		$this->assertEquals($taskCount + 1, Task::count());

		$this->assertDatabaseHas('tasks', $attributes);
	}

	/** @test */
	public function it_is_scheduled_when_created()
	{
		$this->actingAs($this->user)
			->post('/task_lists/'.$this->list->id.'/tasks', $attributes = [
				'title' => 'foobar',
				'interval' => 7,
			]);

		$task = Task::where($attributes)->latest()->first();
		$this->assertCount(1, $task->scheduled_tasks);
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
			->withoutExceptionHandling()
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
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id.'/tasks/'.$task->id),
			]);
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
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id.'/tasks/'.$task->id),
			]);
		tap($task->fresh(), function ($task) {
			$this->assertEquals('barbaz', $task->title);
			$this->assertEquals(4, $task->interval);
		});
	}

	/** @test */
	public function it_can_be_updated_to_be_repeated_daily()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw([
			'title' => 'foobar',
			'interval' => 0,
		]));

		$this->actingAs($this->user)
			->patch('/task_lists/'.$this->list->id.'/tasks/'.$task->id, [
				'title' => 'foobar',
				'interval' => 1,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id.'/tasks/'.$task->id),
			]);
		tap($task->fresh(), function ($task) {
			$this->assertEquals(1, $task->interval);
		});
	}

	/** @test */
	public function it_can_be_updated_to_be_repeated_every_other_day()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw([
			'title' => 'foobar',
			'interval' => 0,
		]));

		$this->actingAs($this->user)
			->patch('/task_lists/'.$this->list->id.'/tasks/'.$task->id, [
				'title' => 'foobar',
				'interval' => 2,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id.'/tasks/'.$task->id),
			]);
		tap($task->fresh(), function ($task) {
			$this->assertEquals(2, $task->interval);
		});
	}

	/** @test */
	public function it_can_be_updated_to_be_repeated_weekly()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw([
			'title' => 'foobar',
			'interval' => 0,
		]));

		$this->actingAs($this->user)
			->patch('/task_lists/'.$this->list->id.'/tasks/'.$task->id, [
				'title' => 'foobar',
				'interval' => 7,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id.'/tasks/'.$task->id),
			]);
		tap($task->fresh(), function ($task) {
			$this->assertEquals(7, $task->interval);
		});
	}

	/** @test */
	public function it_can_be_updated_to_be_repeated_every_other_week()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw([
			'title' => 'foobar',
			'interval' => 0,
		]));

		$this->actingAs($this->user)
			->patch('/task_lists/'.$this->list->id.'/tasks/'.$task->id, [
				'title' => 'foobar',
				'interval' => 14,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id.'/tasks/'.$task->id),
			]);
		tap($task->fresh(), function ($task) {
			$this->assertEquals(14, $task->interval);
		});
	}

	/** @test */
	public function it_can_be_updated_to_be_repeated_monthly()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw([
			'title' => 'foobar',
			'interval' => 0,
		]));

		$this->actingAs($this->user)
			->patch('/task_lists/'.$this->list->id.'/tasks/'.$task->id, [
				'title' => 'foobar',
				'interval' => 30,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id.'/tasks/'.$task->id),
			]);
		tap($task->fresh(), function ($task) {
			$this->assertEquals(30, $task->interval);
		});
	}

	/** @test */
	public function it_can_be_updated_to_be_repeated_every_other_month()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw([
			'title' => 'foobar',
			'interval' => 0,
		]));

		$this->actingAs($this->user)
			->patch('/task_lists/'.$this->list->id.'/tasks/'.$task->id, [
				'title' => 'foobar',
				'interval' => 60,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id.'/tasks/'.$task->id),
			]);
		tap($task->fresh(), function ($task) {
			$this->assertEquals(60, $task->interval);
		});
	}

	/** @test */
	public function it_can_be_updated_to_be_repeated_every_week_on_mondays_and_thursdays()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw([
			'title' => 'foobar',
			'interval' => 0,
		]));

		$this->actingAs($this->user)
			->patch('/task_lists/'.$this->list->id.'/tasks/'.$task->id, [
				'title' => 'foobar',
				'interval' => 7,
				'days' => [
					'mon' => true,
					'tue' => false,
					'wed' => false,
					'thu' => true,
					'fri' => false,
					'sat' => false,
					'sun' => false,
				],
				'data' => [
					'interval' => 77,
				],
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id.'/tasks/'.$task->id),
			]);
		tap($task->fresh(), function ($task) {
			$this->assertEquals(7, $task->interval);
			$this->assertEquals(['interval' => 77], $task->data);
			$this->assertEquals([
				'mon' => true,
				'tue' => false,
				'wed' => false,
				'thu' => true,
				'fri' => false,
				'sat' => false,
				'sun' => false,
			], $task->days);
		});
	}

	/** @test */
	public function it_can_be_updated_to_be_repeated_every_4_weeks()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw([
			'title' => 'foobar',
			'interval' => 0,
		]));

		$this->actingAs($this->user)
			->patch('/task_lists/'.$this->list->id.'/tasks/'.$task->id, [
				'title' => 'foobar',
				'interval' => 28,
				'data' => [
					'interval' => 88,
					'weeks' => 4,
				],
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id.'/tasks/'.$task->id),
			]);
		tap($task->fresh(), function ($task) {
			$this->assertEquals(28, $task->interval);
			$this->assertEquals(['interval' => 88, 'weeks' => 4], $task->data);
		});
	}

	/** @test */
	public function it_can_be_updated_to_be_repeated_every_4_months()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw([
			'title' => 'foobar',
			'interval' => 0,
		]));

		$this->actingAs($this->user)
			->patch('/task_lists/'.$this->list->id.'/tasks/'.$task->id, [
				'title' => 'foobar',
				'interval' => 120,
				'data' => [
					'interval' => 99,
					'months' => 4,
				],
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id.'/tasks/'.$task->id),
			]);
		tap($task->fresh(), function ($task) {
			$this->assertEquals(120, $task->interval);
			$this->assertEquals(['interval' => 99, 'months' => 4], $task->data);
		});
	}

	/** @test */
	public function it_can_be_updated_to_be_repeated_between_dates()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw([
			'title' => 'foobar',
			'interval' => 0,
		]));

		$this->actingAs($this->user)
			->patch('/task_lists/'.$this->list->id.'/tasks/'.$task->id, [
				'title' => 'foobar',
				'interval' => 7,
				'starts_at' => '2010-01-01',
				'ends_at' => '2020-01-01',
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id.'/tasks/'.$task->id),
			]);
		tap($task->fresh(), function ($task) {
			$this->assertEquals(7, $task->interval);
		    $this->assertEquals('2010-01-01', $task->starts_at->format('Y-m-d'));
		    $this->assertEquals('2020-01-01', $task->ends_at->format('Y-m-d'));
		});
	}

	/** @test */
	public function it_can_be_updated_to_be_optional()
	{
		$task = $this->list->tasks()->create(factory(Task::class)->raw([
			'title' => 'foobar',
			'interval' => 0,
		]));

		$this->actingAs($this->user)
			->patch('/task_lists/'.$this->list->id.'/tasks/'.$task->id, [
				'title' => 'foobar',
				'interval' => 7,
				'optional' => true,
			])
			->assertJson([
				'status' => 200,
				'redirect' => url('/task_lists/'.$this->list->id.'/tasks/'.$task->id),
			]);
		tap($task->fresh(), function ($task) {
			$this->assertTrue($task->optional);
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
			->assertRedirect('/task_lists/'.$this->list->id);
		$this->assertEquals($taskCount - 1, Task::count());

		$task = $this->list->tasks()->create(factory(Task::class)->raw());

		$taskCount = Task::count();
		$this->actingAs($this->user)
			->delete('/task_lists/'.$this->list->id.'/tasks/'.$task->id)
			->assertRedirect('/task_lists/'.$this->list->id);
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
