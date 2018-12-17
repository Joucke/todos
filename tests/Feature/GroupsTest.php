<?php

namespace Tests\Feature;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupsTest extends TestCase
{
	use RefreshDatabase;

	public function setUp()
	{
		parent::setUp();

		$this->user = factory(User::class)->create();
	}

	/** @test */
	public function only_signed_in_users_can_list_groups()
	{
	    $this->get('/groups')
	    	->assertRedirect('/login');

	    $this->actingAs($this->user)
	    	->get('/groups')
	    	->assertOk();
	}

	/** @test */
	public function it_lists_all_groups_for_current_user()
	{
		$this->user = factory(User::class)->create();
		$jane = factory(User::class)->create();

		$family = factory(Group::class)->create();
		$work = factory(Group::class)->create();

		$this->user->groups()->attach($family);
		$jane->groups()->attach($work);

	    $this->actingAs($this->user)
	    	->get('/groups')
	    	->assertOk()
	    	->assertViewHas('groups', function($groups) use ($family, $work) {
	    		return $groups->pluck('id')->contains($family->id) &&
	    			!$groups->pluck('id')->contains($work->id);
	    	});
	}

	/** @test */
	public function a_group_can_be_created()
	{
		$this->actingAs($this->user)
			->get('/groups/create')
			->assertOk()
			->assertViewIs('groups.create');

		$groupCount = $this->user->owned_groups->count();
		$this->actingAs($this->user)
			->post('/groups', [
				'title' => 'foobar',
			])
			->assertRedirect('/groups');
		$this->assertEquals($groupCount + 1, $this->user->fresh()->owned_groups->count());
	}

	/** @test */
	public function a_user_becomes_owner_on_creating_a_group()
	{
		$this->withoutExceptionHandling();
		$this->actingAs($this->user)
			->post('/groups', [
				'title' => 'foobar',
			])
			->assertRedirect('/groups');

		$this->assertTrue(Group::first()->owner->is($this->user));
	}

	/** @test */
	public function it_can_list_all_users_for_current_group()
	{
		$group = factory(Group::class)->create();

		$jane = factory(User::class)->create();

		$group->users()->attach($this->user);
		$group->users()->attach($jane);

		$this->actingAs($this->user)
			->get('/groups/'.$group->id)
			->assertOk()
			->assertViewIs('groups.show')
			->assertViewHas('group', function ($group) use ($jane) {
				return $group->users->pluck('id')->contains($this->user->id) &&
					$group->users->pluck('id')->contains($jane->id);
			});
	}

	/** @test */
	public function only_group_members_can_view_a_group()
	{
		$group = factory(Group::class)->create();

		$jane = factory(User::class)->create();

		$group->users()->attach($this->user);

		$this->actingAs($this->user)
			->get('/groups/'.$group->id)
			->assertOk();

		$this->actingAs($jane)
			->get('/groups/'.$group->id)
			->assertForbidden();
	}

	/** @test */
	public function a_group_owner_can_update_a_group()
	{
		$group = factory(Group::class)->create([
			'owner_id' => $this->user->id,
			'title' => 'barbaz'
		]);

		$this->actingAs($this->user)
			->get('/groups/'.$group->id.'/edit')
			->assertOk()
			->assertViewIs('groups.edit');

		$this->actingAs($this->user)
			->patch('/groups/'.$group->id, ['title' => 'foobar'])
			->assertRedirect('/groups');

		$this->assertEquals('foobar', $group->fresh()->title);
	}

	/** @test */
	public function a_group_member_cannot_edit_a_group()
	{
		$group = factory(Group::class)->create([
			'owner_id' => $this->user->id,
			'title' => 'barbaz'
		]);

		$otherUser = factory(User::class)->create();
		$group->users()->attach($otherUser);

		$this->actingAs($otherUser)
			->get('/groups/'.$group->id.'/edit')
			->assertForbidden();

		$this->actingAs($otherUser)
			->patch('/groups/'.$group->id, ['title' => 'foobar'])
			->assertForbidden();
	}

	/** @test */
	public function a_group_owner_can_delete_a_group()
	{
		$group = factory(Group::class)->create([
			'owner_id' => $this->user->id,
			'title' => 'barbaz'
		]);

		$groupCount = $this->user->owned_groups->count();
		$this->actingAs($this->user)
			->delete('/groups/'.$group->id)
			->assertRedirect('/groups');

		$this->assertEquals($groupCount - 1, $this->user->fresh()->owned_groups->count());
	}

	/** @test */
	public function a_group_member_cannot_delete_a_group()
	{
		$group = factory(Group::class)->create([
			'owner_id' => $this->user->id,
			'title' => 'barbaz'
		]);

		$jane = factory(User::class)->create();
		$group->users()->attach($jane);

		$groupCount = $this->user->owned_groups->count();
		$this->actingAs($jane)
			->delete('/groups/'.$group->id)
			->assertForbidden();

		$this->assertEquals($groupCount, $this->user->fresh()->owned_groups->count());
	}
}
