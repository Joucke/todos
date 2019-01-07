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
            ->assertViewHas('groups', function ($groups) use ($task) {
                return $groups->contains('id', $task->task_list->group_id);
            })
            ->assertViewHas('groups', function ($groups) use ($completed) {
                $scheduled = $groups->first()->task_lists->first()->tasks->first()->incompleted_scheduled_tasks;
                return !$scheduled->contains('id', $completed->id);
            })
            ->assertViewHas('groups', function ($groups) use ($incompleted) {
                $scheduled = $groups->first()->task_lists->first()->tasks->first()->incompleted_scheduled_tasks;
                return $scheduled->contains('id', $incompleted->id);
            })
            ->assertViewHas('groups', function ($groups) use ($non_task) {
                return !$groups->contains('id', $non_task->task_list->group_id);
            });
    }
}
