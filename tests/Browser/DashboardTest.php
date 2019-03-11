<?php

namespace Tests\Browser;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DashboardTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp():void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_has_tabs_for_each_group()
    {
        $first = $this->createGroup($this->user);
        $second = $this->createGroup($this->user);
        $third = $this->createGroup($this->user);

        $jane = factory(User::class)->create();
        $fourth = $this->createGroup($jane);

        $this->browse(function (Browser $browser) use ($first, $second, $third, $fourth) {

            $browser->loginAs($this->user)
                ->visit('/dashboard/')
                ->assertSeeIn('main', $first->title)
                ->assertSeeIn('main', $second->title)
                ->assertSeeIn('main', $third->title)
                ->assertDontSeeIn('main', $fourth->title);
        });
    }

    /** @test */
    public function it_has_tabs_to_switch_between_groups()
    {
        $first = $this->createGroup($this->user);
        $listOne = $first->task_lists()->create(['title' => 'foobar']);
        $firstTask = $listOne->tasks()->create(['title' => 'Go to the store', 'interval' => 7])->schedule();

        $second = $this->createGroup($this->user);
        $listTwo = $second->task_lists()->create(['title' => 'barbaz']);
        $secondTask = $listTwo->tasks()->create(['title' => 'Perform', 'interval' => 1])->schedule();

        $this->browse(function (Browser $browser) use ($first, $second) {

            $browser->loginAs($this->user)
                ->visit('/dashboard/')
                ->assertSee('Go to the store')
                ->assertDontSee('Perform')
                ->click("#tab_{$second->id}")
                ->assertDontSee('Go to the store')
                ->assertSee('Perform');
        });
    }

    /** @test */
    public function tabs_are_ordered_by_group_sort_order()
    {
        $first = $this->createGroup($this->user);
        $second = $this->createGroup($this->user);

        $this->user->groups()->sync([
            $first->id => ['sort_order' => 1],
            $second->id => ['sort_order' => 2],
        ]);

        $this->browse(function (Browser $browser) use ($first, $second) {

            $browser->loginAs($this->user)
                ->visit('/dashboard/')
                ->assertSeeIn('main .tabs .card-header li:first-child', $first->title)
                ->assertSeeIn('main .tabs .card-header li:last-child', $second->title);
        });

        $this->user->groups()->sync([
            $first->id => ['sort_order' => 2],
            $second->id => ['sort_order' => 1],
        ]);

        $this->browse(function (Browser $browser) use ($first, $second) {

            $browser->loginAs($this->user)
                ->visit('/dashboard/')
                ->assertSeeIn('main .tabs .card-header li:last-child', $first->title)
                ->assertSeeIn('main .tabs .card-header li:first-child', $second->title);
        });
    }

    /** @test */
    public function it_lists_tasks_ordered_by_task_list_then_scheduled_at()
    {
        $this->browse(function (Browser $browser) {

            $group = $this->createGroup($this->user);

            $listTwo = $group->task_lists()->create([
                'title' => 'barbaz',
                'sort_order' => 2,
            ]);
            $secondTask = tap($listTwo->tasks()->create(['title' => 'Perform second', 'interval' => 1]), function ($task) {
                $task->schedule(2);
            });
            $thirdTask = tap($listTwo->tasks()->create(['title' => 'Perform first', 'interval' => 1]), function ($task) {
                $task->schedule(1);
            });

            $listOne = $group->task_lists()->create([
                'title' => 'foobar',
                'sort_order' => 1,
            ]);
            $firstTask = tap($listOne->tasks()->create(['title' => 'Go to the store', 'interval' => 7]), function ($task) {
                $task->schedule();
            });

            $browser->loginAs($this->user)
                ->visit('/dashboard/')
                ->assertSeeIn('main .tabs .card-container:first-child', $thirdTask->title)
                ->assertSeeIn('main .tabs .card-container:first-child', $secondTask->title)
                ->assertSeeIn('main .tabs .card-container:last-child', $firstTask->title)
                ;
        });
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
