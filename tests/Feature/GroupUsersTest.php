<?php

namespace Tests\Feature;

use App\Group;
use App\User;
use Tests\TestCase;

class GroupUsersTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function a_group_owner_can_remove_a_user_from_a_group()
    {
        $group = factory(Group::class)->create([
            'owner_id' => $this->user->id,
        ]);

        $jane = factory(User::class)->create();
        $group->users()->attach($jane);
        $groupUserCount = $group->users->count();

        $this->actingAs($this->user)
            ->delete('/groups/'.$group->id.'/users/'.$jane->id)
            ->assertJson([
                'status' => 200,
            ]);

        $this->assertCount($groupUserCount - 1, $group->fresh()->users);
    }

    /** @test */
    public function a_group_member_cannot_remove_a_user_from_a_group()
    {
        $group = factory(Group::class)->create([
            'owner_id' => $this->user->id,
        ]);

        $jane = factory(User::class)->create();
        $group->users()->attach($jane);

        $jack = factory(User::class)->create();
        $group->users()->attach($jack);

        $groupUserCount = $group->users->count();

        $this->actingAs($jane)
            ->delete('/groups/'.$group->id.'/users/'.$jack->id)
            ->assertForbidden();

        $this->assertCount($groupUserCount, $group->fresh()->users);
    }

    /** @test */
    public function a_group_member_can_leave_a_group()
    {
        $group = factory(Group::class)->create([
            'owner_id' => $this->user->id,
        ]);

        $jane = factory(User::class)->create();
        $group->users()->attach($jane);

        $this->actingAs($jane)
            ->delete('/groups/'.$group->id.'/users/'.$jane->id)
            ->assertRedirect('/dashboard')
            ->assertSessionHas('status', __('groups.you_left'));
    }
}
