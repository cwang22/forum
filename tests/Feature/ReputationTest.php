<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReputationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_earns_points_when_they_create_a_thread ()
    {
        $thread = create(Thread::class);

        $this->assertEquals(10, $thread->owner->reputation);
    }

    /** @test */
    public function a_user_loses_points_when_they_delete_a_thread ()
    {
        $this->signIn();
        $thread = create(Thread::class, [
            'user_id' => auth()->id()
        ]);

        $this->assertEquals(10, $thread->owner->reputation);

        $this->delete($thread->path());

        $this->assertEquals(0, $thread->owner->fresh()->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_they_reply_a_thread ()
    {
        $thread = create(Thread::class);

        $reply = $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'body'
        ]);

        $this->assertEquals(2, $reply->owner->reputation);
    }

    /** @test */
    public function a_user_loses_points_when_they_delete_a_reply ()
    {
        $thread = create(Thread::class);

        $this->signIn();

        $reply = $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'body'
        ]);

        $this->assertEquals(2, $reply->owner->reputation);

        $this->delete('/replies/' . $reply->id);

        $this->assertEquals(0, $reply->owner->fresh()->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_their_reply_is_marked_as_best ()
    {
        $thread = create(Thread::class);

        $thread->markBestReply($reply = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Here is a reply.'
        ]));

        $this->assertEquals(52, $reply->owner->reputation);
    }

    /** @test */
    public function a_users_points_changes_when_the_best_reply_changes()
    {
        $thread = create(Thread::class);

        $thread->markBestReply($reply = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Here is a reply.'
        ]));

        $this->assertEquals(52, $reply->owner->reputation);

        $thread->markBestReply($anotherReply = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Here is a reply.'
        ]));

        $this->assertEquals(2, $reply->owner->fresh()->reputation);
        $this->assertEquals(52, $anotherReply->owner->reputation);
    }
}
