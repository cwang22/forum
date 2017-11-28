<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_thread_owner_can_mark_best_reply()
    {
        $this->signIn();
        $thread = create(Thread::class, [
            'user_id' => auth()->id()
        ]);
        $replies = create(Reply::class, [
            'thread_id' => $thread->id
        ], 2);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-replies.store', $replies[1]->id));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function only_the_thread_creator_may_mark_a_reply_as_best()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $replies = create('App\Reply', ['thread_id' => $thread->id], 2);
        $this->signIn(create('App\User'));
        $this->postJson(route('best-replies.store', [$replies[1]->id]))->assertStatus(403);
        $this->assertFalse($replies[1]->fresh()->isBest());
    }

    /** @test */
    function if_a_best_reply_is_deleted_then_the_thread_should_also_be_updated()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $reply->thread->markBestReply($reply);
        $this->deleteJson(route('replies.destroy', $reply));
        $this->assertNull($reply->thread->fresh()->best_reply_id);
    }
}
