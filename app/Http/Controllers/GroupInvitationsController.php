<?php

namespace App\Http\Controllers;

use App\Group;
use App\Invitation;
use App\Mail\GroupInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GroupInvitationsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Group $group)
    {
        $this->authorize('update', $group);

        $invite = $group->invitations()->create(
            $request->validate([
                'email' => 'required|email',
            ])
        );

        Mail::to($invite->email)
            ->send(new GroupInvite($invite));

        return [
            'status' => 200,
            'invited' => true,
            'message' => __('groups.invite_sent', ['email' => $invite->email]),
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @param  \App\Invitation  $invite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group, Invitation $invite)
    {
        abort_unless(auth()->user()->is($invite->user), 403);

        $invite->update($request->validate([
            'accepted' => 'required|boolean',
        ]));

        if ($invite->accepted) {
            $group->users()->attach(auth()->id());
            return redirect(route('groups.show', $group))
                ->with('status', __('invitations.statuses.accepted'));
        }

        return redirect(route('invitations'))
                ->with('status', __('invitations.statuses.declined'));
    }
}
