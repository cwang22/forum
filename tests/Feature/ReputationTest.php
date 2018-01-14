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
    public function a_user_earns_points_when_their_reply_is_marked_as_best ()
    {
        $thread = create(Thread::class);

        $thread->markBestReply($reply = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Here is a reply.'
        ]));

        $this->assertEquals(52, $reply->owner->reputation);
    }
}
