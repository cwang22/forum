<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function unauthenticated_users_cannot_add_replies()
    {
        $this->withExceptionHandling();
        $this->post('/threads/some-slug/1/replies', [])
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_reply_to_a_thread()
    {
        $this->signIn();
        $thread = create(Thread::class);
        $reply = make(Reply::class);
        $this->post($thread->path() . '/replies', $reply->toArray());
        $this->get($thread->path())->assertSee($reply->body);
    }

    /** @test */
    public function unauthenticated_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();
        $reply = create(Reply::class);

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()->delete("/replies/{$reply->id}")
        ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_can_delete_a_thread()
    {
        $this->signIn();
        $reply = create(Reply::class, ['user_id' => auth()->id()]);
        $this->delete("/replies/{$reply->id}")->assertStatus(302);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }
}
