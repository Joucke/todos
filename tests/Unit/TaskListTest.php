<?php

namespace Tests\Unit;

use App\Group;
use App\Task;
use App\TaskList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TaskListTest extends TestCase
{
	/** @test */
	public function these_fields_are_fillable()
	{
	    $list = new TaskList([
	    	'title' => 'foobar',
	    	'group_id' => 1,
	    	'sort_order' => 5,
	    ]);

	    $this->assertEquals('foobar', $list->title);
	    $this->assertEquals(1, $list->group_id);
	    $this->assertEquals(5, $list->sort_order);
	}

	/** @test */
	public function it_has_a_sort_field()
	{
	    $list = factory(TaskList::class)->create([
	    	'sort_order' => 5,
	    ]);

	    $this->assertEquals(5, $list->sort_field);
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

	/** @test */
	public function it_has_completed_tasks()
	{
		$list = factory(TaskList::class)->create();

		// fake events so we don't get auto scheduled tasks
		Event::fake();
		$task = $list->tasks()->create(factory(Task::class)->raw());

		Carbon::setTestNow(Carbon::parse('1 week ago'));
		$last_week = tap($task->schedule(0), function ($scheduled_task) {
			$scheduled_task->complete();
		});

		Carbon::setTestNow();
		Carbon::setTestNow(Carbon::parse('yesterday'));
		$yesterday = tap($task->schedule(0), function ($scheduled_task) {
			$scheduled_task->complete();
		});

		Carbon::setTestNow();
		Carbon::setTestNow(Carbon::parse('2 days ago'));
		$day_before = tap($task->schedule(0), function ($scheduled_task) {
			$scheduled_task->complete();
		});

		Carbon::setTestNow();
		Carbon::setTestNow(Carbon::parse('1 month ago'));
		$last_month = $task->schedule(0);

		$completed = $list->completed_tasks()->get();
		$this->assertTrue($completed->contains('id', $last_week->id));
		$this->assertTrue($completed->contains('id', $yesterday->id));
		$this->assertTrue($completed->contains('id', $day_before->id));

		$this->assertFalse($completed->contains('id', $last_month->id));
	}

	/** @test */
	public function completed_tasks_are_sorted_desc_by_completed_at()
	{
		$list = factory(TaskList::class)->create();

		// fake events so we don't get auto scheduled tasks
		Event::fake();
		$task = $list->tasks()->create(factory(Task::class)->raw());

		Carbon::setTestNow(Carbon::parse('1 week ago'));
		$last_week = tap($task->schedule(0), function ($scheduled_task) {
			$scheduled_task->complete();
		});

		Carbon::setTestNow();
		Carbon::setTestNow(Carbon::parse('yesterday'));
		$yesterday = tap($task->schedule(0), function ($scheduled_task) {
			$scheduled_task->complete();
		});

		Carbon::setTestNow();
		Carbon::setTestNow(Carbon::parse('2 days ago'));
		$day_before = tap($task->schedule(0), function ($scheduled_task) {
			$scheduled_task->complete();
		});

		$completed = $list->completed_tasks()->get()->pluck('id')->toArray();
		$this->assertEquals([$yesterday->id, $day_before->id, $last_week->id], $completed);
	}
}
