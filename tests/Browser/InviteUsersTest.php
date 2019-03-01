<?php

namespace Tests\Browser;

use App\Group;
use App\Invitation;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class InviteUsersTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_group_owner_can_invite_a_user_to_a_group()
    {
        $this->browse(function (Browser $browser) {

            $group = factory(Group::class)->create();
            $jane = factory(User::class)->create();

            $browser
                ->loginAs($group->owner)
                ->visit('/groups/'.$group->id)
                ->click('#invite')
                ->whenAvailable('#invite-modal', function ($modal) use ($jane) {
                    $modal
                        ->type('email', $jane->email)
                        ->press(__('groups.invite'))
                        ->waitForText(__('groups.invite_sent', ['email' => $jane->email]), 100);
                });

            $this->assertCount(1, $jane->fresh()->invitations);

            $browser->logout();
        });
    }

    /** @test */
    public function a_group_owner_can_cancel_a_group_invite()
    {
        $this->browse(function (Browser $browser) {

            $group = factory(Group::class)->create();
            $jane = factory(User::class)->create();
            $invite = $group->invitations()->create(factory(Invitation::class)->raw([
                'email' => $jane->email,
            ]));

            $browser
                ->loginAs($group->owner)
                ->visit('/dashboard')
                ->clickLink(__('invitations.by_me'))
                ->assertRouteIs('invites.index')
                ->press(__('invitations.cancel'))
                ->assertDontSee($group->title)
                ->assertDontSee($jane->email)
                ->assertSee(__('invitations.statuses.deleted'))
                ->assertDontSee('invitations.statuses.deleted');

            $this->assertCount(0, $jane->fresh()->invitations);

            $browser->logout();
        });
    }

    /** @test */
    public function an_invited_user_can_open_an_invitation_from_any_page()
    {
        $this->browse(function (Browser $browser) {

            $group = factory(Group::class)->create();
            $jane = factory(User::class)->create();
            $invite = $group->invitations()->create(factory(Invitation::class)->raw([
                'email' => $jane->email,
            ]));

            $browser
                ->loginAs($jane)
                ->visit('/dashboard/')
                ->assertSee(__('groups.invited', ['group' => $group->title]))

                ->clickLink($group->title)
                ->assertRouteIs('invitations');

            $browser->logout();
        });
    }

    /** @test */
    public function an_invited_user_can_accept_an_invitation()
    {
        $this->browse(function (Browser $browser) {

            $group = factory(Group::class)->create();
            $jane = factory(User::class)->create();
            $invite = $group->invitations()->create(factory(Invitation::class)->raw([
                'email' => $jane->email,
            ]));

            $browser
                ->loginAs($jane)
                ->visit('/dashboard')
                ->clickLink(__('invitations.to_me'))
                ->assertRouteIs('invitations')
                ->press(__('invitations.accept'))
                ->assertRouteIs('groups.show', ['group' => $group])
                ->assertSee(__('invitations.statuses.accepted'))
                ->assertDontSee('invitations.statuses.accepted');

            $this->assertTrue($jane->fresh()->groups->contains('id', $group->id));
            $this->assertTrue($invite->fresh()->accepted);

            $browser->logout();
        });
    }

    /** @test */
    public function an_invited_user_can_decline_an_invitation()
    {
        $this->browse(function (Browser $browser) {

            $group = factory(Group::class)->create();
            $jane = factory(User::class)->create();
            $invite = $group->invitations()->create(factory(Invitation::class)->raw([
                'email' => $jane->email,
            ]));

            $browser
                ->loginAs($jane)
                ->visit('/dashboard')
                ->clickLink(__('invitations.to_me'))
                ->assertRouteIs('invitations')
                ->press(__('invitations.decline'))
                ->assertRouteIs('invitations')
                ->assertDontSee($group->title)
                ->assertSee(__('invitations.statuses.declined'))
                ->assertDontSee('invitations.statuses.declined');

            $this->assertFalse($jane->fresh()->groups->contains('id', $group->id));
            $this->assertFalse($invite->fresh()->accepted);

            $browser->logout();
        });
    }
}
