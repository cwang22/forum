<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadSubscriptionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $this->signIn();
        $thread = create(Thread::class);
        $this->post($thread->path().'/subscriptions');
        $this->assertCount(1, $thread->subscriptions);
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $this->signIn();
        $thread = create(Thread::class);
        $thread->subscribe();
        $this->delete($thread->path().'/subscriptions');
        $this->assertCount(0, $thread->subscriptions);
    }
}
