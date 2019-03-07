<?php

namespace Tests\Unit;

use App\ScheduledTask;
use App\Task;
use App\TaskList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
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
	    	'days' => [
	    		'mon' => true,
	    		'tue' => true,
				'wed' => true,
				'thu' => true,
				'fri' => true,
				'sat' => true,
				'sun' => true,
			],
			'data' => [
				'interval' => 77,
	    	],
	    	'optional' => true,
	    	'starts_at' => now()->format('Y-m-d'),
	    	'ends_at' => now()->format('Y-m-d'),
	    ]);

	    $this->assertEquals('go to the store', $task->title);
	    $this->assertEquals(1, $task->task_list_id);
	    $this->assertEquals(1, $task->interval);
	    $this->assertEquals([
    		'mon' => true,
    		'tue' => true,
			'wed' => true,
			'thu' => true,
			'fri' => true,
			'sat' => true,
			'sun' => true,
		], $task->days);
	    $this->assertEquals(['interval' => 77], $task->data);
		$this->assertTrue($task->optional);
	    $this->assertEquals(now()->format('Y-m-d'), $task->starts_at->format('Y-m-d'));
	    $this->assertEquals(now()->format('Y-m-d'), $task->ends_at->format('Y-m-d'));
	}

	/** @test */
	public function it_casts_optional_to_boolean()
	{
	    $task = factory(Task::class)->create(['optional' => 1]);

	    $this->assertTrue($task->fresh()->optional);
	}

	/** @test */
	public function it_appends_an_url()
	{
	    $task = factory(Task::class)->create();

	    $taskArray = $task->toArray();

	    $this->assertTrue(array_key_exists('url', $taskArray));
	    $this->assertEquals(route('tasks.completed_tasks.store', $task), $taskArray['url']);
	}

	/** @test */
	public function it_casts_days_and_data_to_an_array()
	{
	    $task = factory(Task::class)->create([
	    	'days' => [
	    		'mon' => true,
	    		'tue' => true,
				'wed' => true,
				'thu' => true,
				'fri' => true,
				'sat' => true,
				'sun' => true,
			],
			'data' => [
				'interval' => 77,
	    	],
	    ]);

		$this->assertTrue(is_array($task->days));
		$this->assertTrue(is_array($task->data));
	}

	/** @test */
	public function it_casts_start_and_end_to_dates()
	{
	    $task = new Task([
	    	'title' => 'go to the store',
	    	'task_list_id' => 1,
	    	'interval' => 1,
	    	'days' => [
	    		'mon' => true,
	    		'tue' => true,
				'wed' => true,
				'thu' => true,
				'fri' => true,
				'sat' => true,
				'sun' => true,
			],
			'data' => [
				'interval' => 77,
	    	],
	    	'optional' => true,
	    	'starts_at' => now()->format('Y-m-d'),
	    	'ends_at' => now()->format('Y-m-d'),
	    ]);

	    $this->assertInstanceOf(Carbon::class, $task->starts_at);
	    $this->assertInstanceOf(Carbon::class, $task->ends_at);
	}

	/** @test */
	public function it_gets_a_text_interval_string_for_daily()
	{
	    $task = factory(Task::class)->create([
	    	'interval' => 1,
	    	'data' => [
	    		'interval' => 1,
	    	],
	    ]);

	    $this->assertEquals(__('tasks.intervals.1'), $task->text_interval);
	}

	/** @test */
	public function it_gets_a_text_representation_for_days()
	{
	    $task = factory(Task::class)->create([
	    	'interval' => 1,
	    	'data' => [
	    		'interval' => 1,
	    	],
	    	'days' => [
	    		'mon' => true,
	    		'tue' => false,
				'wed' => false,
				'thu' => true,
				'fri' => false,
				'sat' => false,
				'sun' => false,
			],
	    ]);

	    $this->assertEquals(__('tasks.mon').', '.__('tasks.thu'), $task->text_days);
	}

	/** @test */
	public function it_gets_a_text_interval_string_for_other_daily()
	{
	    $task = factory(Task::class)->create([
	    	'interval' => 2,
	    	'data' => [
	    		'interval' => 2,
	    	],
	    ]);

	    $this->assertEquals(__('tasks.intervals.2'), $task->text_interval);
	}

	/** @test */
	public function it_gets_a_text_interval_string_for_weekly()
	{
	    $task = factory(Task::class)->create([
	    	'interval' => 7,
	    	'data' => [
	    		'interval' => 7,
	    	],
	    ]);

	    $this->assertEquals(__('tasks.intervals.7'), $task->text_interval);
	}

	/** @test */
	public function it_gets_a_text_interval_string_for_other_weekly()
	{
	    $task = factory(Task::class)->create([
	    	'interval' => 14,
	    	'data' => [
	    		'interval' => 14,
	    	],
	    ]);

	    $this->assertEquals(__('tasks.intervals.14'), $task->text_interval);
	}

	/** @test */
	public function it_gets_a_text_interval_string_for_weekly_on()
	{
	    $task = factory(Task::class)->create([
	    	'interval' => 77,
	    	'data' => [
	    		'interval' => 77,
	    	],
	    ]);

	    $this->assertEquals(__('tasks.intervals.77'), $task->text_interval);
	}

	/** @test */
	public function it_gets_a_text_interval_string_for_monthly()
	{
	    $task = factory(Task::class)->create([
	    	'interval' => 30,
	    	'data' => [
	    		'interval' => 30,
	    	],
	    ]);

	    $this->assertEquals(__('tasks.intervals.30'), $task->text_interval);
	}

	/** @test */
	public function it_gets_a_text_interval_string_for_other_monthly()
	{
	    $task = factory(Task::class)->create([
	    	'interval' => 60,
	    	'data' => [
	    		'interval' => 60,
	    	],
	    ]);

	    $this->assertEquals(__('tasks.intervals.60'), $task->text_interval);
	}

	/** @test */
	public function it_gets_a_text_interval_string_for_every_x_weeks()
	{
	    $task = factory(Task::class)->create([
	    	'interval' => 88,
	    	'data' => [
	    		'interval' => 88,
	    		'weeks' => 4
	    	],
	    ]);

	    $this->assertEquals(__('tasks.intervals.88', ['weeks' => 4]), $task->text_interval);
	}

	/** @test */
	public function it_gets_a_text_interval_string_for_every_x_months()
	{
	    $task = factory(Task::class)->create([
	    	'interval' => 88,
	    	'data' => [
	    		'interval' => 88,
	    		'months' => 4
	    	],
	    ]);

	    $this->assertEquals(__('tasks.intervals.88', ['months' => 4]), $task->text_interval);
	}

	/** @test */
	public function it_belongs_to_a_task_list()
	{
	    $list = factory(TaskList::class)->create();

	    $task = factory(Task::class)->create(['task_list_id' => $list->id]);

	    $this->assertTrue($task->task_list->is($list));
	}

	/** @test */
	public function it_can_schedule_a_task()
	{
		$interval = 1;
	    $task = factory(Task::class)->create(['interval' => $interval]);
	    $scheduled = $task->schedule();

	    $this->assertInstanceOf(ScheduledTask::class, $scheduled);
	    $this->assertEquals(now()->addDays($interval)->format("Y-m-d H:i"), $scheduled->scheduled_at->format("Y-m-d H:i"));
	}

	/** @test */
	public function it_can_schedule_a_task_on_an_interval()
	{
		$interval = 1;
	    $task = factory(Task::class)->create(['interval' => $interval]);
	    $scheduled = $task->schedule(2);

	    $this->assertInstanceOf(ScheduledTask::class, $scheduled);
	    $this->assertEquals(now()->addDays(2)->format("Y-m-d H:i"), $scheduled->scheduled_at->format("Y-m-d H:i"));
	}

	/** @test */
	public function it_can_schedule_a_task_between_dates()
	{
	    $task = factory(Task::class)->create([
	    	'interval' => 3,
	    	'starts_at' => now()->subDays(20),
	    	'ends_at' => now()->addDays(2),
	    ]);

	    $scheduled = $task->schedule();

	    tap($task->fresh(), function ($task) use ($scheduled) {
		    $this->assertEquals(now()->subDays(20)->addYear()->format('Y-m-d'), $task->starts_at->format('Y-m-d'));
		    $this->assertEquals(now()->addDays(2)->addYear()->format('Y-m-d'), $task->ends_at->format('Y-m-d'));
		    $this->assertEquals($task->starts_at, $scheduled->scheduled_at);
	    });
	}

	/** @test */
	public function it_can_schedule_a_task_on_certain_days()
	{
	    $task = factory(Task::class)->create([
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
	    ]);
	    Carbon::setTestNow(new Carbon('next monday'));

	    $scheduled = $task->schedule();

	    $this->assertEquals('Thursday', $scheduled->scheduled_at->englishDayOfWeek);

	    Carbon::setTestNow(new Carbon('next friday'));

	    $scheduled = $task->schedule();

	    $this->assertEquals('Monday', $scheduled->scheduled_at->englishDayOfWeek);
	}

	/** @test */
	public function it_has_many_scheduled_tasks()
	{
	    $task = factory(Task::class)->create();
	    $today = factory(ScheduledTask::class)->create(['task_id' => $task->id, 'scheduled_at' => now()]);
	    $tomorrow = factory(ScheduledTask::class)->create(['task_id' => $task->id, 'scheduled_at' => now()->addDay()]);

	    $this->assertTrue($task->scheduled_tasks->contains('id', $today->id));
	    $this->assertTrue($task->scheduled_tasks->contains('id', $tomorrow->id));
	}

	/** @test */
	public function it_has_many_incompleted_scheduled_tasks()
	{
	    $task = factory(Task::class)->create();
	    $completed = factory(ScheduledTask::class)->create(['task_id' => $task->id, 'scheduled_at' => now()]);
	    $incompleted = factory(ScheduledTask::class)->create(['task_id' => $task->id, 'scheduled_at' => now()->addDay()]);
	    $completed->complete();

	    tap($task->fresh(), function ($task) use ($completed, $incompleted) {
		    $this->assertFalse($task->incompleted_scheduled_tasks->contains('id', $completed->id));
		    $this->assertTrue($task->incompleted_scheduled_tasks->contains('id', $incompleted->id));
	    });
	}
}
