<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LockThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function non_administrator_may_not_lock_a_thread()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create(Thread::class, [
            'user_id' => auth()->id()
        ]);

        $this->post(route('locked-threads', $thread))->assertStatus(403);
    }

    /** @test */
    public function an_administrator_can_lock_a_thread()
    {
        $this->signIn(factory(User::class)->states('administrator')->create());
        $thread = create(Thread::class, [
            'user_id' => auth()->id()
        ]);

        $this->post(route('locked-threads', $thread));

        $this->assertTrue($thread->fresh()->locked, 'Failed asserting that the thread was locked.');
    }

    /** @test */
    public function an_administrator_can_unlock_a_thread()
    {
        $this->signIn(factory(User::class)->states('administrator')->create());
        $thread = create(Thread::class, [
            'user_id' => auth()->id(),
            'locked' => true
        ]);

        $this->delete(route('locked-threads', $thread));
        $this->assertFalse($thread->fresh()->locked, 'Failed asserting that the thread was not locked.');

    }


    /** @test */
    public function a_locked_thread_cannot_be_replied()
    {
        $this->signIn();

        $thread = create(Thread::class, [
            'locked' => true
        ]);

        $this->post($thread->path() . '/replies', [
            'body' => 'foobar',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }
}
