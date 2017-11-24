<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LockThreadTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function non_administrator_may_not_lock_a_thread()
    {
        $this->signIn();

        $thread = create(Thread::class, [
            'user_id' => auth()->id()
        ]);

        $this->patch($thread->path(), [
            'locked' => true
        ])->assertStatus(403);
    }

    /** @test */
    public function a_locked_thread_cannot_be_replied()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->lock();

        $this->post($thread->path() . '/replies', [
            'body' => 'foobar',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }
}
