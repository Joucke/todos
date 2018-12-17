<?php

namespace Tests\Unit;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UserTest extends TestCase
{
	use RefreshDatabase;

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
}
