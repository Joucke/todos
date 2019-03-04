<?php

namespace Tests\Browser;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SortingGroupsTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp():void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function groups_can_be_sorted()
    {
        [$home, $work] = factory(Group::class, 2)->create();
        $this->user->groups()->sync([
            $home->id => ['sort_order' => 1],
            $work->id => ['sort_order' => 2],
        ]);

        $this->browse(function (Browser $browser) use ($home, $work) {

            $this->assertEquals([$home->id, $work->id], $this->user->groups->pluck('id')->toArray());

            $browser->loginAs($this->user)
                ->visit('/groups/')
                ->click('.down')
                ->pause(1000)
                ->assertSeeIn('tbody tr:first-child', $work->title)
                ->assertSeeIn('tbody tr:last-child', $home->title);

            $this->assertEquals([$work->id, $home->id], $this->user->groups()->get()->pluck('id')->toArray());

            $browser->click('.up')
                ->pause(1000)
                ->assertSeeIn('tbody tr:first-child', $home->title)
                ->assertSeeIn('tbody tr:last-child', $work->title);

            $this->assertEquals([$home->id, $work->id], $this->user->groups()->get()->pluck('id')->toArray());
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
