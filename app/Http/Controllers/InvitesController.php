<?php

namespace App\Http\Controllers;

use App\Invitation;
use Illuminate\Http\Request;

class InvitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invites = auth()->user()->invites()->with('group')->get();
        return view('invites.index', compact('invites'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invitation  $invitation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invitation $invite)
    {
        $this->authorize('update', $invite->group);

        $invite->delete();

        return redirect(route('invites.index'))->with('status', __('invitations.statuses.deleted'));
    }
}
