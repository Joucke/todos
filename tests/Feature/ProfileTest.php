<?php

namespace Tests\Feature;

use App\Group;
use App\User;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    /** @test */
    public function a_user_can_see_their_own_profile()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get('/users/'.$user->id)
            ->assertOk()
            ->assertViewIs('users.show')
            ->assertViewHas('user', $user);
    }

    /** @test */
    public function group_owners_can_view_a_user_profile()
    {
        $group = factory(Group::class)->create();
        $user = factory(User::class)->create();
        $group->users()->attach($user);

        $this->actingAs($group->owner)
            ->get('/users/'.$user->id)
            ->assertOk()
            ->assertViewIs('users.show')
            ->assertViewHas('user', $user);
    }

    /** @test */
    public function group_members_can_view_a_user_profile()
    {
        $group = factory(Group::class)->create();

        $jane = factory(User::class)->create();
        $john = factory(User::class)->create();

        $group->users()->attach($jane);
        $group->users()->attach($john);

        $this->actingAs($jane)
            ->get('/users/'.$john->id)
            ->assertOk()
            ->assertViewIs('users.show')
            ->assertViewHas('user', $john);
    }

    /** @test */
    public function group_members_can_view_the_group_owners_profile()
    {
        $group = factory(Group::class)->create();
        $user = factory(User::class)->create();
        $group->users()->attach($user);

        $this->actingAs($user)
            ->get('/users/'.$group->owner->id)
            ->assertOk()
            ->assertViewIs('users.show')
            ->assertViewHas('user', $group->owner);
    }

    /** @test */
    public function non_group_members_cannot_view_a_user_profile()
    {
        $home = factory(Group::class)->create();
        $work = factory(Group::class)->create();

        $jane = factory(User::class)->create();
        $john = factory(User::class)->create();

        $home->users()->attach($jane);
        $work->users()->attach($john);

        $this->actingAs($jane)
            ->get('/users/'.$john->id)
            ->assertForbidden();
    }

    /** @test */
    public function it_contains_their_name()
    {
        $group = factory(Group::class)->create();

        $jane = factory(User::class)->create();
        $john = factory(User::class)->create();

        $group->users()->attach($jane);
        $group->users()->attach($john);

        $this->actingAs($jane)
            ->get('/users/'.$john->id)
            ->assertSee($john->name);
    }

    /** @test */
    public function it_contains_their_groups()
    {
        [$home, $work, $school] = factory(Group::class, 3)->create();
        $user = factory(User::class)->create();
        $user->groups()->attach([$home->id, $work->id]);

        $response = $this->actingAs($user)
            ->get('/users/'.$user->id);

        $response
            ->assertViewHas('user', $user);

        $viewUser = $response->original->getData()['user'];
        $this->assertTrue($viewUser->groups->contains('id', $home->id));
        $this->assertTrue($viewUser->groups->contains('id', $work->id));
        $this->assertFalse($viewUser->groups->contains('id', $school->id));
    }

    /** @test */
    public function it_contains_their_email()
    {
        $group = factory(Group::class)->create();

        $jane = factory(User::class)->create();
        $john = factory(User::class)->create();

        $group->users()->attach($jane);
        $group->users()->attach($john);

        $this->actingAs($jane)
            ->get('/users/'.$john->id)
            ->assertSee($john->email);
    }

    /** @test */
    public function a_user_can_edit_their_profile()
    {
        $group = factory(Group::class)->create();
        $user = factory(User::class)->create();
        $group->users()->attach($user);

        $this->actingAs($user)
            ->get('/users/'.$user->id.'/edit')
            ->assertOk()
            ->assertViewIs('users.edit')
            ->assertViewHas('user', $user);

        $this->actingAs($user)
            ->patch('/users/'.$user->id, [
                'name' => 'John',
                'email' => 'john@example.com',
            ])
            ->assertRedirect('/users/'.$user->id);

        tap($user->fresh(), function ($user) {
            $this->assertEquals('John', $user->name);
            $this->assertEquals('john@example.com', $user->email);
        });
    }

    /** @test */
    public function a_user_cannot_edit_other_users_profiles()
    {
        $group = factory(Group::class)->create();

        $jane = factory(User::class)->create();
        $john = factory(User::class)->create();

        $group->users()->attach($jane);
        $group->users()->attach($john);

        $this->actingAs($jane)
            ->get('/users/'.$john->id.'/edit')
            ->assertForbidden();

        $this->actingAs($jane)
            ->patch('/users/'.$john->id, [
                'name' => 'John',
                'email' => 'john@example.com',
            ])
            ->assertForbidden();
    }
}
