<?php

namespace Tests\Unit;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function these_fields_are_fillable()
	{
		$group = new Group(['title' => 'foobar']);

		$this->assertEquals('foobar', $group->title);
	}

	/** @test */
	public function it_has_multiple_users()
	{
		$john = factory(User::class)->create();
		$jane = factory(User::class)->create();

		$group = factory(Group::class)->create();

		$group->users()->attach($john);
		$group->users()->attach($jane);

		tap($group->users->pluck('id'), function($users) use ($john, $jane) {
			$this->assertContains($john->id, $users);
			$this->assertContains($jane->id, $users);
		});
	}
}
