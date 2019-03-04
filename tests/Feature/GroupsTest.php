<?php

namespace Tests\Feature;

use App\Group;
use App\User;
use Tests\TestCase;

class GroupsTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();

		$this->user = factory(User::class)->create();
	}

	/** @test */
	public function a_user_can_create_a_group()
	{
		$this->actingAs($this->user)
			->get('/groups/create')
			->assertOk()
			->assertViewIs('groups.create');

		$groupCount = Group::count();
		$response = $this->actingAs($this->user)
			->post('/groups', [
				'title' => 'foobar',
			]);

		$group = Group::latest()->first();
		$response->assertRedirect('/groups/'.$group->id);
		$this->assertEquals($groupCount + 1, Group::count());
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
	public function it_lists_all_users_for_a_group()
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
				$users = $group->users->pluck('id');
				return $users->contains($this->user->id)
					&& $users->contains($jane->id);
			});
	}

	/** @test */
	public function only_group_members_can_view_a_group()
	{
		$group = factory(Group::class)->create();
		$group->users()->attach($this->user);

		$jane = factory(User::class)->create();

		$this->actingAs($this->user)
			->get('/groups/'.$group->id)
			->assertOk();

		$this->actingAs($jane)
			->get('/groups/'.$group->id)
			->assertForbidden();
	}

	/** @test */
	public function a_group_owner_can_edit_a_group()
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
	public function a_group_member_can_view_a_list_of_their_groups()
	{
		[$home, $work] = factory(Group::class, 2)->create();
		$school = factory(Group::class)->create();
		$user = factory(User::class)->create();
		$user->groups()->attach([$home->id, $work->id]);

		$response = $this->actingAs($user)
			->get('/groups');

		$response
			->assertOk()
			->assertViewIs('groups.index')
			->assertViewHas('groups', $user->groups);

		$groups = $response->original->getData()['groups'];
		$this->assertTrue($groups->contains('id', $home->id));
		$this->assertTrue($groups->contains('id', $work->id));
		$this->assertFalse($groups->contains('id', $school->id));
	}

	/** @test */
	public function a_group_member_can_sort_their_groups()
	{
		[$home, $work] = factory(Group::class, 2)->create();
		$user = factory(User::class)->create();
		$user->groups()->attach([$home->id, $work->id]);

        $this->assertEquals([$home->id, $work->id], $user->groups->pluck('id')->toArray());

		$this->actingAs($user)
			->patch('/users/'.$user->id.'/groups', [
				'user_group_order' => [
					$work->id => ['sort_order' => 1],
					$home->id => ['sort_order' => 2],
				],
			])
			->assertJson([
				'status' => 200,
				'items' => [
					$work->toArray(),
					$home->toArray(),
				],
			]);

		tap($user->fresh(), function ($user) use ($work, $home) {
	        $this->assertEquals([$work->id, $home->id], $user->groups->pluck('id')->toArray());
		});
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
}
