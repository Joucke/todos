<?php

namespace Tests\Browser;

use App\Group;
use App\TaskList;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TaskListsTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp():void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_shows_breadcrumbs()
    {
        $group = $this->createGroup($this->user);
        $list = factory(TaskList::class)->create(['group_id' => $group->id]);

        $this->browse(function (Browser $browser) use ($group, $list) {

            $browser->loginAs($this->user)
                ->visit('/task_lists/'.$list->id)
                ->assertSeeIn('h1.page-header', $group->title)
                ->assertSeeIn('h1.page-header', $list->title)
                ->assertSeeLink($group->title);
        });
    }

    /** @test */
    public function the_group_owner_can_sort_task_lists()
    {
        $this->browse(function (Browser $browser) {
            $group = $this->createGroup($this->user);
            [$first, $second] = factory(TaskList::class, 2)->create(['group_id' => $group->id]);

            $browser->loginAs($this->user)
                ->visit('/groups/'.$group->id)
                ->click('.down')
                ->pause(1000)
                ->assertSeeIn('tbody tr:first-child', $second->title)
                ->assertSeeIn('tbody tr:last-child', $first->title);

            $this->assertEquals([$second->id, $first->id], $group->task_lists()->get()->pluck('id')->toArray());

            $browser->click('.up')
                ->pause(1000)
                ->assertSeeIn('tbody tr:first-child', $first->title)
                ->assertSeeIn('tbody tr:last-child', $second->title);

            $this->assertEquals([$first->id, $second->id], $group->task_lists()->get()->pluck('id')->toArray());
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
