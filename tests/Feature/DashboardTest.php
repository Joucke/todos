<?php

namespace Tests\Feature;

use App\Group;
use App\Task;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /** @test */
    public function guests_cannot_open_dashboard()
    {
        $this->get('/dashboard')
            ->assertRedirect('login');
    }

    /** @test */
    public function it_lists_incompleted_scheduled_tasks_for_users()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create();
        $task->task_list->group->users()->attach($user);
        $non_task = factory(Task::class)->create();

        $completed = $task->schedule();
        $completed->complete();
        $incompleted = $task->schedule();
        $non_task->schedule();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertViewIs('dashboard')
            ->assertViewHas('my_groups', function ($groups) use ($task) {
                return $groups['scheduled']->contains('id', $task->task_list->group_id);
            })
            ->assertViewHas('my_groups', function ($groups) use ($completed) {
                $scheduled = $groups['scheduled']->first()->tasks->first()->incompleted_scheduled_tasks;
                return !$scheduled->contains('id', $completed->id);
            })
            ->assertViewHas('my_groups', function ($groups) use ($incompleted) {
                $scheduled = $groups['scheduled']->first()->tasks->first()->incompleted_scheduled_tasks;
                return $scheduled->contains('id', $incompleted->id);
            })
            ->assertViewHas('my_groups', function ($groups) use ($non_task) {
                return !$groups['scheduled']->contains('id', $non_task->task_list->group_id);
            });
    }

    /** @test */
    public function it_lists_unscheduled_tasks_for_users()
    {
        $user = factory(User::class)->create();
        $unscheduled = factory(Task::class)->create();
        $unscheduled->task_list->group->users()->attach($user);
        $non_task = factory(Task::class)->create();

        $this->actingAs($user)
            ->withoutExceptionHandling()
            ->get('/dashboard')
            ->assertViewIs('dashboard')
            ->assertViewHas('my_groups', function ($groups) use ($unscheduled) {
                return $groups['unscheduled']->contains('id', $unscheduled->task_list->group_id);
            })
            ->assertViewHas('my_groups', function ($groups) use ($non_task) {
                return !$groups['unscheduled']->contains('id', $non_task->task_list->group_id);
            });
    }

    /** @test */
    public function it_sorts_scheduled_tasks_by_scheduled_at()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_sorts_unscheduled_tasks_by_interval_then_created_at()
    {
        $this->markTestIncomplete();
    }
}
