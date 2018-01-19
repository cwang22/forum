<?php

namespace App\Http\Controllers;

use App\User;
use App\Activity;

class ProfilesController extends Controller
{
    /**
     * show profile for given user.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('profiles.show', [
            'profileUser' => $user,
            'activities' => Activity::feed($user)
        ]);
    }
}
