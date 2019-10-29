<?php

namespace Tests\Unit;

use App\Group;
use App\Invitation;
use App\Mail\GroupInvite;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupInviteMailTest extends TestCase
{
    /** @test */
    public function it_renders_for_existing_users()
    {
        $group = factory(Group::class)->create();
        $user = factory(User::class)->create();
        $invitation = factory(Invitation::class)->create([
            'group_id' => $group->id,
            'email' => $user->email,
        ]);

        $mail = new GroupInvite($invitation);
        $view = $mail->render();

        $this->assertStringContainsString(__('groups.mails.existing.message', ['group' => $group->title]), $view);
        $this->assertStringContainsString(__('groups.mails.existing.call_to_action'), $view);
        $this->assertStringContainsString(route('invitations'), $view);

        $this->assertStringNotContainsString('groups.mails.existing.title', $view);
        $this->assertStringNotContainsString('groups.mails.existing.message', $view);
        $this->assertStringNotContainsString('groups.mails.existing.call_to_action', $view);
    }

    /** @test */
    public function it_renders_for_new_users()
    {
        $group = factory(Group::class)->create();
        $invitation = factory(Invitation::class)->create([
            'group_id' => $group->id,
            'email' => 'jane@example.com',
        ]);

        $mail = new GroupInvite($invitation);
        $view = $mail->render();

        $this->assertStringContainsString(__('groups.mails.new_user.message', ['group' => $group->title]), $view);
        $this->assertStringContainsString(__('groups.mails.new_user.call_to_action'), $view);
        $this->assertStringContainsString(route('register'), $view);

        $this->assertStringNotContainsString('groups.mails.new_user.title', $view);
        $this->assertStringNotContainsString('groups.mails.new_user.message', $view);
        $this->assertStringNotContainsString('groups.mails.new_user.call_to_action', $view);
    }
}
