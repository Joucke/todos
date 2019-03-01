<?php

namespace Tests\Feature;

use App\Group;
use App\Invitation;
use App\Mail\GroupInvite;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InviteUsersTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        Mail::fake();
    }

    /** @test */
    public function a_group_owner_can_invite_an_existing_user_to_a_group()
    {
        $group = factory(Group::class)->create([
            'owner_id' => $this->user->id,
        ]);

        $jane = factory(User::class)->create(['email' => 'jane@example.com']);

        $this->actingAs($this->user)
            ->post('/groups/'.$group->id.'/invites', [
                'email' => 'jane@example.com',
            ])
            ->assertJson([
                'status' => 200,
                'invited' => true,
                'message' => __('groups.invite_sent', ['email' => 'jane@example.com']),
            ]);

        $this->assertCount(1, $jane->invitations);
        $this->assertCount(1, $this->user->invites);

        Mail::assertSent(GroupInvite::class, function ($mail) use ($group, $jane) {
            return $mail->group->is($group)
                && $mail->hasTo('jane@example.com')
                && $mail->inviter->is($this->user)
                && $mail->user->is($jane);
        });
    }

    /** @test */
    public function a_group_owner_cannot_invite_an_existing_member_to_a_group()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function a_group_owner_can_invite_a_new_user_to_a_group()
    {
        $group = factory(Group::class)->create([
            'owner_id' => $this->user->id,
        ]);

        $this->actingAs($this->user)
            ->post('/groups/'.$group->id.'/invites', [
                'email' => 'jane@example.com',
            ])
            ->assertJson([
                'status' => 200,
                'invited' => true,
                'message' => __('groups.invite_sent', ['email' => 'jane@example.com']),
            ]);

        $this->assertDatabaseHas('invitations', [
            'email' => 'jane@example.com',
            'group_id' => $group->id,
        ]);
        $this->assertCount(1, $this->user->invites);

        Mail::assertSent(GroupInvite::class, function ($mail) use ($group) {
            return $mail->group->is($group)
                && $mail->hasTo('jane@example.com')
                && $mail->inviter->is($this->user)
                && $mail->user === null;
        });
    }

    /** @test */
    public function a_group_owner_can_view_their_invites()
    {
        $group = factory(Group::class)->create([
            'owner_id' => $this->user->id,
        ]);

        $jane = factory(User::class)->create();

        $invite = $group->invitations()->create(factory(Invitation::class)->raw([
            'email' => $jane->email,
        ]));

        $this->actingAs($this->user)
            ->withoutExceptionHandling()
            ->get('/invites')
            ->assertOk()
            ->assertSee($group->title)
            ->assertSee($jane->email);
    }

    /** @test */
    public function it_displays_a_message_when_there_are_no_invites()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_hides_the_menu_item_when_there_are_no_invites()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function a_group_owner_can_cancel_their_invites()
    {
        $group = factory(Group::class)->create([
            'owner_id' => $this->user->id,
        ]);

        $jane = factory(User::class)->create();

        $invite = $group->invitations()->create(factory(Invitation::class)->raw([
            'email' => $jane->email,
        ]));

        $this->assertCount(1, $jane->fresh()->invitations);
        $this->assertCount(1, $group->fresh()->invitations);
        $this->assertCount(1, $this->user->fresh()->invites);

        $this->actingAs($this->user)
            ->delete('/invites/'.$invite->id)
            ->assertRedirect('/invites')
            ->assertSessionHas('status', __('invitations.statuses.deleted'))
            ;

        $this->assertCount(0, $jane->fresh()->invitations);
        $this->assertCount(0, $group->fresh()->invitations);
        $this->assertCount(0, $this->user->fresh()->invites);
    }

    /** @test */
    public function a_group_member_cannot_invite_users_to_a_group()
    {
        $group = factory(Group::class)->create(['owner_id' => $this->user->id]);

        [$jane, $jack] = factory(User::class, 2)->create();
        $group->users()->attach($jane);

        $this->actingAs($jane)
            ->post('groups/'.$group->id.'/invites', [
                'email' => $jack->email,
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('invitations', [
            'email' => $jack->email,
            'group_id' => $group->id,
        ]);

        Mail::assertNotSent(GroupInvite::class);
    }

    /** @test */
    public function another_user_cannot_cancel_invites()
    {
        $group = factory(Group::class)->create([
            'owner_id' => $this->user->id,
        ]);

        $jane = factory(User::class)->create();

        $invite = $group->invitations()->create(factory(Invitation::class)->raw([
            'email' => $jane->email,
        ]));

        $this->assertCount(1, $jane->fresh()->invitations);
        $this->assertCount(1, $group->fresh()->invitations);
        $this->assertCount(1, $this->user->fresh()->invites);

        $this->actingAs($jane)
            ->delete('/invites/'.$invite->id)
            ->assertForbidden()
            ;

        $this->assertCount(1, $jane->fresh()->invitations);
        $this->assertCount(1, $group->fresh()->invitations);
        $this->assertCount(1, $this->user->fresh()->invites);
    }

    /** @test */
    public function an_invited_user_can_view_their_invitations()
    {
        $group = factory(Group::class)->create([
            'owner_id' => $this->user->id,
        ]);

        $jane = factory(User::class)->create();

        $invite = $group->invitations()->create(factory(Invitation::class)->raw([
            'email' => $jane->email,
        ]));

        $this->actingAs($jane)
            ->withoutExceptionHandling()
            ->get('/invitations')
            ->assertOk()
            ->assertSee($group->title);
    }

    /** @test */
    public function it_displays_a_message_when_there_are_no_invitations()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_hides_the_menu_item_when_there_are_no_invitations()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function an_invited_user_can_accept_a_group_invitation()
    {
        $group = factory(Group::class)->create([
            'owner_id' => $this->user->id,
        ]);

        $jane = factory(User::class)->create();

        $invite = $group->invitations()->create(factory(Invitation::class)->raw([
            'email' => $jane->email,
        ]));

        $this->assertCount(0, $jane->groups);
        $this->assertCount(1, $jane->invitations);

        $this->actingAs($jane)
            ->withoutExceptionHandling()
            ->patch('/groups/'.$group->id.'/invites/'.$invite->id, [
                'accepted' => 1,
            ])
            ->assertRedirect('/groups/'.$group->id);

        $this->assertTrue($invite->fresh()->accepted);

        tap($jane->fresh(), function ($jane) {
            $this->assertCount(1, $jane->groups);
            $this->assertCount(0, $jane->invitations);
        });
    }

    /** @test */
    public function an_invited_user_can_decline_a_group_invitation()
    {
        $group = factory(Group::class)->create([
            'owner_id' => $this->user->id,
        ]);

        $jane = factory(User::class)->create();

        $invite = $group->invitations()->create(factory(Invitation::class)->raw([
            'email' => $jane->email,
        ]));

        $this->assertCount(0, $jane->groups);
        $this->assertCount(1, $jane->invitations);

        $this->actingAs($jane)
            ->patch('/groups/'.$group->id.'/invites/'.$invite->id, [
                'accepted' => 0,
            ])
            ->assertRedirect('/invitations');

        tap($jane->fresh(), function ($jane) {
            $this->assertCount(0, $jane->groups);
            $this->assertCount(0, $jane->invitations);
        });
    }

    /** @test */
    public function a_user_cannot_accept_or_decline_another_users_invitation()
    {
        $group = factory(Group::class)->create([
            'owner_id' => $this->user->id,
        ]);

        $jane = factory(User::class)->create();

        $invite = $group->invitations()->create(factory(Invitation::class)->raw([
            'email' => $jane->email,
        ]));

        $this->assertCount(0, $jane->groups);
        $this->assertCount(1, $jane->invitations);

        $this->actingAs($this->user)
            ->patch('/groups/'.$group->id.'/invites/'.$invite->id, [
                'accepted' => true,
            ])
            ->assertForbidden();

        tap($jane->fresh(), function ($jane) {
            $this->assertCount(0, $jane->groups);
            $this->assertCount(1, $jane->invitations);
        });

        $this->actingAs($this->user)
            ->patch('/groups/'.$group->id.'/invites/'.$invite->id, [
                'accepted' => false,
            ])
            ->assertForbidden();

        tap($jane->fresh(), function ($jane) {
            $this->assertCount(0, $jane->groups);
            $this->assertCount(1, $jane->invitations);
        });
    }

    /** @test */
    public function a_new_user_who_was_invited_sees_their_invitations_upon_registering()
    {
        $group = factory(Group::class)->create([
            'owner_id' => $this->user->id,
        ]);

        $invite = $group->invitations()->create(factory(Invitation::class)->raw([
            'email' => 'jane@example.com',
        ]));

        $jane = factory(User::class)->create(['email' => 'jane@example.com']);

        $this->actingAs($jane)
            ->get('/dashboard')
            ->assertSee(__('groups.invited', ['group' => $group->title]));
    }
}
