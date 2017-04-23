<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReadThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @var  Thread */
    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = create(Thread::class);
    }

    /** @test */
    public function a_user_can_browse_threads()
    {
        $this->get('/threads')->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_browse_a_thread()
    {
        $this->get($this->thread->path())->assertSee($this->thread->title);
    }

    /** @test */
    public function a_thread_can_have_replies()
    {
        $reply = create(Reply::class, ['thread_id' => $this->thread->id]);
        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create(Channel::class);
        $threadInChannel = create(Thread::class, ['channel_id' => $channel->id]);
        $threadNotInChannel = create(Thread::class);
        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_owner()
    {
        $user = create(User::class);
        $threadByUser = create(Thread::class, ['user_id' => $user->id]);
        $threadNotByUser = create(Thread::class);

        $this->get('/threads?by='.$user->name)
            ->assertSee($threadByUser->title)
            ->assertDontSee($threadNotByUser->title);
    }
}