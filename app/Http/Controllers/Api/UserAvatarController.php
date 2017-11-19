<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class UserAvatarController extends Controller
{
    /**
     * Store uploaded avatar
     *
     * @param \App\User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store($user)
    {
        $this->validate(request(), [
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
