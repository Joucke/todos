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
    public function it_lists_tasks_ordered_by_task_list_then_scheduled_at()
    {
        $group = $this->createGroup($this->user);
        $listOne = $group->task_lists()->create(['title' => 'foobar']);
        $firstTask = $listOne->tasks()->create(['title' => 'Go to the store', 'interval' => 7])->schedule();

        $listTwo = $group->task_lists()->create(['title' => 'barbaz']);
        $secondTask = $listTwo->tasks()->create(['title' => 'Perform second', 'interval' => 1])->schedule(2);
        $thirdTask = $listTwo->tasks()->create(['title' => 'Perform first', 'interval' => 1])->schedule(1);

        $this->browse(function (Browser $browser) use ($group) {

            $browser->loginAs($this->user)
                ->visit('/dashboard/')
                ->assertSeeIn('main .tabs .card-container:first-child', 'Perform first')
                ->assertSeeIn('main .tabs .card-container:not(:first-child):not(:last-child)', 'Perform second')
                ->assertSeeIn('main .tabs .card-container:last-child', 'Go to the store')
                ;
        });

        $this->markTestIncomplete('Task List sorting is not there yet');
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
