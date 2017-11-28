@component('mail::message')
    # One Last Step

    We just need your to confirm your email address to complete your registration. :)

    @component('mail::button', ['url' => url('/register/confirm?token=' . $user->confirm_token)])
        Confirm Email
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
