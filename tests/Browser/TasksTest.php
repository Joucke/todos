<?php

namespace Tests\Browser;

use App\Group;
use App\Task;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TasksTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_shows_breadcrumbs()
    {
        $task = factory(Task::class)->create([
            'interval' => 1,
            'data' => [
                'interval' => 1,
            ],
        ]);
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

    /** @test */
    public function a_user_can_add_a_task()
    {
        $this->browse(function (Browser $browser) {
            $user = factory(User::class)->create();
            $group = factory(Group::class)->create([
                'owner_id' => $user->id,
            ]);
            $group->users()->attach($user->id);
            $list = $group->task_lists()->create([
                'title' => 'foobar',
            ]);

            $browser->loginAs($user)
                ->visit('/task_lists/'.$list->id)
                ->clickLink(__('tasks.create'))
                ->assertRouteIs('task_lists.tasks.create', ['task_list' => $list])

                ->type('title', 'Some Title')
                ->assertVue('task.title', 'Some Title', '@task-form-component')
                ->click('label[for=interval_1]')
                ->assertVue('task.interval', '1', '@task-form-component')
                ->click('label[for=interval_2]')
                ->assertVue('task.interval', '2', '@task-form-component')
                ->click('label[for=interval_7]')
                ->assertVue('task.interval', '7', '@task-form-component')
                ->click('label[for=interval_14]')
                ->assertVue('task.interval', '14', '@task-form-component')
                ->click('label[for=interval_30]')
                ->assertVue('task.interval', '30', '@task-form-component')
                ->click('label[for=interval_60]')
                ->assertVue('task.interval', '60', '@task-form-component')
                ->click('label[for=interval_77]')
                ->assertVue('task.interval', '77', '@task-form-component')
                ->assertSeeIn('label[for=interval_77]', __('tasks.mon'))
                ->assertSeeIn('label[for=interval_77]', __('tasks.tue'))
                ->assertSeeIn('label[for=interval_77]', __('tasks.wed'))
                ->assertSeeIn('label[for=interval_77]', __('tasks.thu'))
                ->assertSeeIn('label[for=interval_77]', __('tasks.fri'))
                ->assertSeeIn('label[for=interval_77]', __('tasks.sat'))
                ->assertSeeIn('label[for=interval_77]', __('tasks.sun'))
                ->click('label[for=option_1')
                ->assertVue('task.days', [
                    'mon' => false,
                    'tue' => true,
                    'wed' => false,
                    'thu' => false,
                    'fri' => false,
                    'sat' => false,
                    'sun' => false,
                ], '@task-form-component')
                ->click('label[for=option_5')
                ->assertVue('task.days', [
                    'mon' => false,
                    'tue' => true,
                    'wed' => false,
                    'thu' => false,
                    'fri' => false,
                    'sat' => true,
                    'sun' => false,
                ], '@task-form-component')
                ->click('label[for=interval_88]')
                ->assertVue('task.interval', '88', '@task-form-component')
                ->assertSeeIn('label[for=interval_88]', __('tasks.intervals.88', ['weeks' => 3]))
                ->dragRight('.vue-slider-dot-handle', 50)
                ->assertVue('task.data.weeks', '4', '@task-form-component')
                ->click('label[for=interval_99]')
                ->assertVue('task.interval', '99', '@task-form-component')
                ->assertSeeIn('label[for=interval_99]', __('tasks.intervals.99', ['months' => 3]))
                ->dragRight('.vue-slider-dot-handle', 50)
                ->assertVue('task.data.months', '4', '@task-form-component')

                ->assertDontSee(__('tasks.start'))
                ->assertDontSee(__('tasks.end'))
                ->click('#check-period')
                ->assertVue('task.period', true, '@task-form-component')
                ->assertSee(__('tasks.start'))
                ->type('start', '31032022') // type m-d-Y b/c stupid headless chrome format
                ->assertVue('task.startDate', '2022-03-31', '@task-form-component')
                ->assertSee(__('tasks.end'))
                ->type('end', '30062022') // type m-d-Y b/c stupid headless chrome format
                ->assertVue('task.endDate', '2022-06-30', '@task-form-component')
                ->click('#check-optional')
                ->assertVue('task.optional', true, '@task-form-component')
                ->press(__('tasks.add'))
                ->waitUntilMissing('@task-form-component')
                ->assertRouteIs('task_lists.show', ['task_list' => $list])
                ;

            $this->markTestIncomplete('Get new task and assert stuff on it');
        });
    }
}
