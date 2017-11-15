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
        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
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
    public function an_authenticated_user_can_delete_a_reply()
    {
        $this->signIn();
        $reply = create(Reply::class, ['user_id' => auth()->id()]);
        $this->delete("/replies/{$reply->id}")->assertStatus(302);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    public function unauthenticated_users_cannot_update_replies()
    {
        $this->withExceptionHandling();
        $reply = create(Reply::class);

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_can_edit_a_reply()
    {
        $this->signIn();
        $reply = create(Reply::class, ['user_id' => auth()->id()]);
        $updatedBody = 'Updated';
        $this->patch("/replies/{$reply->id}", ['body' => $updatedBody]);
        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => $updatedBody
            ]);
    }

    /** @test */
    public function replies_that_contain_spam_may_not_be_created()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $reply = make(Reply::class, [
            'body' => 'Yahoo Customer Support'
        ]);

        $this->post($thread->path() . '/replies', $reply->toArray())->assertStatus(422);
    }
}
