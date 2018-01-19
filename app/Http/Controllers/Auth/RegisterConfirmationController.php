<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    /**
     * Confirm a user's email address.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = User::where('confirm_token', request('token'))
            ->first();

        if (! $user) {
            return redirect(route('threads'))->with('flash', 'unknown token.');
        }

        $user->confirm();

        return redirect(route('threads'))->with('flash', 'Your account\'s email is confirmed.');
    }
}
