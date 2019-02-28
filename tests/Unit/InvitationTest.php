<?php

namespace Tests\Unit;

use App\Group;
use App\Invitation;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    /** @test */
    public function these_fields_are_fillable()
    {
        $invitation = new Invitation([
            'email' => 'jane@example.com',
            'group_id' => 1,
            'accepted' => true,
        ]);

        $this->assertEquals('jane@example.com', $invitation->email);
        $this->assertEquals(1, $invitation->group_id);
        $this->assertTrue($invitation->accepted);
    }

    /** @test */
    public function it_casts_accepted_to_boolean()
    {
        $invitation = factory(Invitation::class)->create([
            'accepted' => 0,
        ]);

        $this->assertFalse($invitation->fresh()->accepted);
    }

    /** @test */
    public function it_belongs_to_a_group()
    {
        $group = factory(Group::class)->create();

        $invitation = factory(Invitation::class)->create([
            'group_id' => $group->id,
            'accepted' => 0,
        ]);

        $this->assertTrue($invitation->group->is($group));
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = factory(User::class)->create();

        $invitation = factory(Invitation::class)->create([
            'email' => $user->email,
        ]);

        $this->assertTrue($invitation->user->is($user));
    }
}
