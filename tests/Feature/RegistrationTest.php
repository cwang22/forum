<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => '123456123',
            'password_confirmation' => '123456123'
        ]);

        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function users_can_confirm_their_email_addresses()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => '123456123',
            'password_confirmation' => '123456123'
        ]);

        $user = User::whereName('John')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirm_token);

        $this->get(route('register.confirm', [
            'token' => $user->confirm_token
        ]))->assertRedirect(route('threads'));

        $this->assertTrue($user->fresh()->confirmed);
        $this->assertNull($user->fresh()->confirm_token);
    }

    /** @test */
    public function confirming_an_invalid_token()
    {
        $this->get(route('register.confirm'), ['token' => 'invalid'])
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'unknown token.');
    }
}