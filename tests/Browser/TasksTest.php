<?php

namespace Tests\Browser;

use App\Task;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TasksTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_shows_breadcrumbs()
    {
        $task = factory(Task::class)->create();
        $task->task_list->group->users()->attach($task->task_list->group->owner);

        $this->browse(function (Browser $browser) use ($task) {

            $browser->loginAs($task->task_list->group->owner)
                ->visit('/task_lists/'.$task->task_list_id.'/tasks/'.$task->id)
                ->assertSeeIn('h1.page-header', $task->task_list->group->title)
                ->assertSeeIn('h1.page-header', $task->task_list->title)
                ->assertSeeIn('h1.page-header', $task->title)
                ->assertSeeLink($task->task_list->group->title)
                ->assertSeeLink($task->task_list->title);
        });
    }

}
