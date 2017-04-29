<?php

namespace App\Http\Controllers;

use App\User;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        return view('profiles.show')->with([
            'profileUser' => $user,
            'activities' => $this->getActivities($user)
        ]);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getActivities(User $user)
    {
        return $user->activities()->with('subject')->latest()->get()->groupBy(function ($activity) {
            return $activity->created_at->format('Y-m-d');
        });
    }
}
