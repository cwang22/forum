<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;

class UserAvatarController extends Controller
{
    /**
     * Store uploaded avatar
     *
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(User $user)
    {
        request()->validate([
            'avatar' => ['required', 'image']
        ]);

        auth()->user()->update(
            [
                'avatar_path' => request()->file('avatar')->store('avatars', 'public')
            ]
        );

        return response([], 204);
    }
}
