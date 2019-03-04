<?php

namespace Tests\Unit;

use App\Group;
use App\Invitation;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UserTest extends TestCase
{
	/** @test */
	public function these_fields_are_fillable()
	{
		$user = new User([
			'name' => 'foobar',
			'email' => 'joe@example.com',
			'password' => 'secret',
		]);

		$this->assertEquals('foobar', $user->name);
		$this->assertEquals('joe@example.com', $user->email);
		$this->assertEquals('secret', $user->password);
	}

	/** @test */
	public function these_fields_are_hidden_in_arrays()
	{
		$user = factory(User::class)->create();

		tap($user->fresh()->toArray(), function($userArray) {
			$this->assertFalse(array_key_exists('password', $userArray));
			$this->assertFalse(array_key_exists('remember_token', $userArray));
		});
	}

	/** @test */
	public function it_belongs_to_multiple_groups()
	{
		$family = factory(Group::class)->create();
		$work = factory(Group::class)->create();

		$user = factory(User::class)->create();

		$user->groups()->attach($family);
		$user->groups()->attach($work);

		tap($user->groups->pluck('id'), function($groups) use ($family, $work) {
			$this->assertContains($family->id, $groups);
			$this->assertContains($work->id, $groups);
		});
	}

    /** @test */
    public function groups_are_sorted_by_sort_order()
    {
        $family = factory(Group::class)->create();
        $work = factory(Group::class)->create();

        $user = factory(User::class)->create();

        $user->groups()->attach($family->id, ['sort_order' => 2]);
        $user->groups()->attach($work->id, ['sort_order' => 1]);

        $this->assertEquals([$work->id, $family->id], $user->groups->pluck('id')->toArray());
    }

	/** @test */
	public function a_user_can_own_groups()
	{
		$user = factory(User::class)->create();
		$work = factory(Group::class)->create(['owner_id' => $user->id]);

		$this->assertTrue($work->owner->is($user));
		$this->assertContains($work->id, $user->owned_groups->pluck('id'));
	}

	/** @test */
	public function it_can_have_many_invitations()
	{
        $group = factory(Group::class)->create();

        $jane = factory(User::class)->create();

        $invitation = factory(Invitation::class)->create([
        	'group_id' => $group->id,
            'email' => $jane->email,
        ]);

        tap($jane->fresh(), function ($jane) use ($group, $invitation) {
        	$this->assertCount(1, $jane->invitations);
	        $this->assertContains($invitation->id, $jane->invitations->pluck('id'));
        });
	}

	/** @test */
	public function it_can_have_many_invites_through_group()
	{
		$user = factory(User::class)->create();
        $group = factory(Group::class)->create([
        	'owner_id' => $user->id,
        ]);

        $jane = factory(User::class)->create();

        $invitation = factory(Invitation::class)->create([
        	'group_id' => $group->id,
            'email' => $jane->email,
        ]);

        $this->assertCount(1, $user->invites);
	}

	/** @test */
	public function it_hides_accepted_invitations()
	{
        $group = factory(Group::class)->create();

        $jane = factory(User::class)->create();

        $invitation = factory(Invitation::class)->create([
        	'group_id' => $group->id,
            'email' => $jane->email,
            'accepted' => true,
        ]);

        tap($jane->fresh(), function ($jane) {
        	$this->assertCount(0, $jane->invitations);
        });
	}

    /** @test */
    public function it_hides_declined_invitations()
    {
        $group = factory(Group::class)->create();

        $jane = factory(User::class)->create();

        $invitation = factory(Invitation::class)->create([
            'group_id' => $group->id,
            'email' => $jane->email,
            'accepted' => false,
        ]);

        tap($jane->fresh(), function ($jane) {
            $this->assertCount(0, $jane->invitations);
        });
    }

    /** @test */
    public function it_hides_accepted_invites()
    {
        $group = factory(Group::class)->create();

        $jane = factory(User::class)->create();

        $invitation = factory(Invitation::class)->create([
            'group_id' => $group->id,
            'email' => $jane->email,
            'accepted' => true,
        ]);

        tap($group->owner->fresh(), function ($owner) {
            $this->assertCount(0, $owner->invites);
        });
    }

    /** @test */
    public function it_hides_declined_invites()
    {
        $group = factory(Group::class)->create();

        $jane = factory(User::class)->create();

        $invitation = factory(Invitation::class)->create([
            'group_id' => $group->id,
            'email' => $jane->email,
            'accepted' => false,
        ]);

        tap($group->owner->fresh(), function ($owner) {
            $this->assertCount(0, $owner->invites);
        });
    }
}
