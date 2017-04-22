<?php

namespace Tests\Unit;

use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    /** @var  Thread */
    private $thread;
    use DatabaseMigrations;

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

    protected function setUp()
    {
        parent::setUp();
        $this->thread = create(Thread::class);
    }
}
