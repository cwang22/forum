<?php
namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function notify_subscriber_when_new_reply_added()
    {
        $this->signIn();

        $thread = create(Thread::class)->subscribe();
        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'reply'
        ]);

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'reply'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);


    }
}
