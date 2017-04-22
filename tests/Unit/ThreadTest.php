<?php

namespace Tests\Unit;

use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_a_owner()
    {
        $thread = factory(Thread::class)->create();
        $this->assertInstanceOf(User::class, $thread->owner);
    }

    /** @test */
    public function it_has_replies()
    {
        $thread = factory(Thread::class)->create();
        $this->assertInstanceOf(Collection::class, $thread->replies);
    }
}
