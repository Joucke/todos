<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /** @test */
    public function a_user_can_login()
    {
        $user = factory(User::class)->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret',
        ])->assertRedirect('/dashboard');
    }

    /** @test */
    public function a_guest_can_register()
    {
        $userCount = User::count();

        $this->post('/register', [
            'name' => 'Foo Bar',
            'email' => 'foo-bar@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ])->assertRedirect('/dashboard');

        $this->assertEquals($userCount + 1, User::count());
    }

    /** @test */
    public function a_user_can_reset_their_password()
    {
        $user = factory(User::class)->create();
        $token = Password::broker()->createToken($user);

        $response = $this->post('/password/reset', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'secret01',
            'password_confirmation' => 'secret01',
        ])->assertRedirect('/dashboard');
    }

    /** @test */
    public function it_redirects_a_logged_in_user_from_login_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get('/login')
            ->assertRedirect('/dashboard');
    }
}
