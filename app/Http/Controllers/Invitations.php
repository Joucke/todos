<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Invitations extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $invitations = auth()->user()->invitations()->with('group')->get();
        return view('invitations.index', compact('invitations'));
    }
}
