<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct ()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider ()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Handle callback from Github authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback ()
    {
        $user = $this->FindOrCreateGithubUser(Socialite::driver('github')->user());

        auth()->login($user);

        return redirect($this->redirectTo);
    }

    /**
     * Find or create a user for the github account
     *
     * @param $githubUser
     * @return User $user
     */
    protected function FindOrCreateGithubUser ($githubUser)
    {
        $user = User::firstOrNew(['github_id' => $githubUser->id]);

        if ($user->exists) return $user;

        $user->fill([
            'name' => $githubUser->nickname,
            'email' => $githubUser->email,
            'avatar_path' => $githubUser->avatar
        ])->save();

        return $user;
    }
}
