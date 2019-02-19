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
