<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GroupController extends Controller
{
    /**
     * Display a listing of the user's groups.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Group::class);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $groups = $user->groups()
            ->with(['owner', 'taskLists', 'members'])
            ->get();

        $ownedGroups = $user->ownedGroups()
            ->with(['taskLists', 'members'])
            ->get();

        return view('groups.index', compact('groups', 'ownedGroups'));
    }

    /**
     * Show the form for creating a new group.
     */
    public function create(): View
    {
        $this->authorize('create', Group::class);

        return view('groups.create');
    }

    /**
     * Store a newly created group.
     */
    public function store(StoreGroupRequest $request): RedirectResponse
    {
        $group = Group::create([
            'title' => $request->title,
            'description' => $request->description,
            'visibility' => $request->visibility ?? 'private',
            'owner_id' => auth()->id(),
        ]);

        // Add the creator as the first member
        $group->members()->attach(auth()->id(), ['sort_order' => 1]);

        return redirect()->route('groups.show', $group)
            ->with('success', 'Group created successfully!');
    }

    /**
     * Display the specified group with task lists and tasks.
     */
    public function show(Group $group): View
    {
        $this->authorize('view', $group);

        $group->load([
            'taskLists.tasks' => function ($query) {
                $query->where('starts_at', '<=', now())
                     ->orWhereNull('starts_at');
            },
            'members',
            'pendingInvitations'
        ]);

        return view('groups.show', compact('group'));
    }

    /**
     * Show the form for editing the specified group.
     */
    public function edit(Group $group): View
    {
        $this->authorize('update', $group);

        return view('groups.edit', compact('group'));
    }

    /**
     * Update the specified group.
     */
    public function update(UpdateGroupRequest $request, Group $group): RedirectResponse
    {
        $group->update([
            'title' => $request->title,
        ]);

        return redirect()->route('groups.show', $group)
            ->with('success', 'Group updated successfully!');
    }

    /**
     * Remove the specified group.
     */
    public function destroy(Group $group): RedirectResponse
    {
        $this->authorize('delete', $group);

        $group->delete();

        return redirect()->route('groups.index')
            ->with('success', 'Group deleted successfully!');
    }

    /**
     * Leave a group (for members, not owners).
     */
    public function leave(Group $group): RedirectResponse
    {
        $this->authorize('leave', $group);

        $group->members()->detach(auth()->id());

        return redirect()->route('groups.index')
            ->with('success', 'You have left the group.');
    }

    /**
     * Sort groups for the authenticated user.
     */
    public function sort(Request $request): RedirectResponse
    {
        $request->validate([
            'group_ids' => 'required|array',
            'group_ids.*' => 'exists:groups,id',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        foreach ($request->group_ids as $index => $groupId) {
            $user->groups()->updateExistingPivot($groupId, [
                'sort_order' => $index + 1
            ]);
        }

        return redirect()->back()
            ->with('success', 'Group order updated!');
    }
}
