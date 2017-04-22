<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function a_user_can_browse_threads()
    {
        $this->get('/threads')->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_browse_a_thread()
    {
        $this->get('/thread/' . $this->thread->id)->assertSee($this->thread->title);
    }

    /** @test */
    public function a_thread_can_have_replies()
    {
        $reply = factory(Reply::class)->create(['thread_id' => $this->thread->id]);
        $this->get('/thread/' . $this->thread->id)
            ->assertSee($reply->body);
    }
}