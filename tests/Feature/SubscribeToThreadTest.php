<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscribeToThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_subscribe_to_a_thread()
    {
        $this->signIn();
        $thread = create(Thread::class);
        $this->post($thread->path() . '/subscriptions');
        $this->assertCount(1, $thread->subscriptions);
    }
}
