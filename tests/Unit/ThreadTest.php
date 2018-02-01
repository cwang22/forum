<?php

namespace Tests\Unit;

use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\Reply;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    /** @var  Thread */
    private $thread;
    use RefreshDatabase;

    /** @test */
    public function it_has_a_owner()
    {
        $this->assertInstanceOf(User::class, $this->thread->owner);
    }

    /** @test */
    public function it_has_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /** @test */
    public function it_can_add_reply()
    {

        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);
        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function it_has_a_channel()
    {
        $this->assertInstanceOf(Channel::class, $this->thread->channel);
    }

    /** @test */
    public function it_has_a_path()
    {
        $this->assertEquals(
            "/threads/{$this->thread->channel->slug}/{$this->thread->slug}",
            $this->thread->path()
        );
    }

    /** @test */
    public function it_can_be_subscribed_to()
    {
        $this->thread->subscribe($userId = 1);
        $this->assertEquals(1, $this->thread->subscriptions()->where('user_id', 1)->count());
    }

    /** @test */
    public function it_can_be_unsubscribed_from()
    {
        $this->thread->subscribe($userId = 1);
        $this->thread->unsubscribe($userId);
        $this->assertCount(0, $this->thread->subscriptions);
    }

    /** @test */
    public function it_knows_if_it_it_subscribed()
    {
        $this->signIn();

        $this->assertFalse($this->thread->isSubscribed);

        $this->thread->subscribe();

        $this->assertTrue($this->thread->isSubscribed);

    }

    /** @test */
    function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body' => 'Foobar',
                'user_id' => create(User::class)->id
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    public function it_can_check_if_authenticated_user_has_read_replies()
    {
        $this->signIn();

        tap(auth()->user(), function ($user) {
            $this->assertTrue($this->thread->hasUpdatesFor($user));

            cache()->forever($user->visitedThreadCacheKey($this->thread), Carbon::now());

            $this->assertFalse($this->thread->hasUpdatesFor($user));
        });
    }

    /** @test */
    public function a_threads_body_is_sanitized_automatically()
    {
        $thread = make(Thread::class, ['body' => '<script>alert("hi")</script><p>this is good</p>']);
        $this->assertEquals('<p>this is good</p>', $thread->body);
    }
    
    /** @test */
    public function it_can_have_a_best_reply()
    {
        $this->signIn();
        $thread = create(Thread::class, [
            'user_id' => auth()->id()
        ]);

        $reply = $thread->addReply([
            'body' => 'Foobar',
            'user_id' => create(User::class)->id
        ]);

        $thread->markBestReply($reply);

        $this->assertEquals($reply->id, $thread->bestReply->id);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->thread = create(Thread::class);
    }

}
