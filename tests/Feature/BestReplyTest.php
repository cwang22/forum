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
        $thread = create(Thread::class);
        $replies = create(Reply::class, [
            'thread_id' => $thread->id
        ], 2);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-replies', $replies[1]->id));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }
}
