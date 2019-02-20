<?php

namespace Tests\Feature;

use App\Group;
use App\Task;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /** @test */
    public function guests_cannot_open_the_dashboard()
    {
        $this->get('/dashboard')
            ->assertRedirect('login');
    }

    /** @test */
    public function signed_in_users_can_open_the_dashboard()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk();
    }

    /** @test */
    public function users_without_groups_are_asked_to_create_their_own_group()
    {
        $user = factory(User::class)->create();

        $this->markTestIncomplete();
        $this->actingAs($user)
            ->get('/dashboard')
            ->assertSee('Create a group of your own!');
    }
}
