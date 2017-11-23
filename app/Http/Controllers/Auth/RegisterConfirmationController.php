<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        try {
            User::where('confirm_token', request('token'))
                ->firstOrFail()
                ->confirm();

            return redirect(route('threads'))->with('flash', 'Your account\'s email is confirmed.');
        } catch (\Exception $e) {
            return redirect(route('threads'))->with('flash', 'unknown token.');

        }

    }
}
