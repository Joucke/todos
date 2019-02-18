<?php

namespace Tests\Feature;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupsTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();

		$this->user = factory(User::class)->create();
	}

	/** @test */
	public function a_group_can_be_created()
	{
		$this->actingAs($this->user)
			->get('/groups/create')
			->assertOk()
			->assertViewIs('groups.create');

		$groupCount = $this->user->owned_groups->count();
		$response = $this->actingAs($this->user)
			->post('/groups', [
				'title' => 'foobar',
			]);
		$group = Group::latest()->first();
		$response->assertRedirect('/groups/'.$group->id);
		$this->assertEquals($groupCount + 1, $this->user->fresh()->owned_groups->count());
	}

	/** @test */
	public function it_displays_a_flash_message_after_creating_a_group()
	{
		$response = $this->actingAs($this->user)
			->post('/groups', [
				'title' => 'foobar',
			])
			->assertSessionHas('status', __('groups.statuses.created'));
	}

	/** @test */
	public function a_user_becomes_owner_on_creating_a_group()
	{
		$this->actingAs($this->user)
			->post('/groups', [
				'title' => 'foobar',
			]);

		$this->assertTrue(Group::latest()->first()->owner->is($this->user));
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
			->assertRedirect('/groups/'.$group->id);

		$this->assertEquals('foobar', $group->fresh()->title);
	}

	/** @test */
	public function it_displays_a_flash_message_after_updating_a_group()
	{
		$group = factory(Group::class)->create([
			'owner_id' => $this->user->id,
			'title' => 'barbaz'
		]);

		$response = $this->actingAs($this->user)
			->patch('/groups/'.$group->id, ['title' => 'foobar'])
			->assertSessionHas('status', __('groups.statuses.updated'));
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
			->assertRedirect('/dashboard');

		$this->assertEquals($groupCount - 1, $this->user->fresh()->owned_groups->count());
	}

	/** @test */
	public function it_displays_a_flash_message_after_deleting_a_group()
	{
		$group = factory(Group::class)->create([
			'owner_id' => $this->user->id,
			'title' => 'barbaz'
		]);

		$response = $this->actingAs($this->user)
			->delete('/groups/'.$group->id)
			->assertSessionHas('status', __('groups.statuses.deleted'));
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

	/** @test */
	public function a_group_owner_can_see_all_users()
	{
		$group = factory(Group::class)->create(['owner_id' => $this->user->id]);

		$jane = factory(User::class)->create();

		$group->users()->attach($this->user);

		$this->actingAs($this->user)
			->get('/groups/'.$group->id)
			->assertViewIs('groups.show')
			->assertViewHas('users', User::all());
	}

	/** @test */
	public function a_group_owner_can_add_a_member_to_a_group()
	{
		$john = $this->user;
		$jane = factory(User::class)->create();
	    $group = factory(Group::class)->create(['owner_id' => $john->id]);

	    $this->actingAs($john)
	    	->post('groups/'.$group->id.'/users', [
	    		'user_id' => $jane->id,
	    	])
	    	->assertRedirect('groups/'.$group->id);

	    $this->assertTrue($group->fresh()->users->contains('id', $jane->id));
	}

	/** @test */
	public function a_group_member_cannot_add_another_member_to_a_group()
	{
		$john = $this->user;
		[$jane, $jack] = factory(User::class, 2)->create();
		$group = factory(Group::class)->create(['owner_id' => $john->id]);
		$group->users()->attach($jane);

		$this->actingAs($jane)
			->post('groups/'.$group->id.'/users', [
				'user_id' => $jack->id,
			])
			->assertForbidden();

		$this->assertFalse($group->fresh()->users->contains('id', $jack->id));
	}

	/** @test */
	public function a_group_owner_can_invite_a_user_to_a_group()
	{
	    $this->markTestIncomplete();
	    // this is a more privacy safe way of adding users to groups.
	    // let the admin input an email address
	    // if the address exists as a user, send the user an invite to join the group
	    // if the address does not exist, invite the user to join, and prepare an invite to join the group

	    // this will replace @a_group_owner_can_add_a_member_to_a_group and should get a new test for @a_group_member_cannot_add_another_member_to_a_group

	    // upon implementation, the pulldown targeted in @a_group_owner_can_see_all_users should also be removed.
	}
}
