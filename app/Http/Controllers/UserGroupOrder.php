<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserGroupOrder extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, User $user)
    {
        $user->groups()->sync($request->input('user_group_order'));

        return [
            'status' => 200,
            'items' => $user->groups
        ];
    }
}
