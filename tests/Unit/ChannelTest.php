<?php

namespace Tests\Feature;

use App\Channel;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_threads()
    {
        $channel = create(Channel::class);
        $thread = create(Thread::class, ['channel_id' => $channel->id]);
        $this->assertTrue($channel->threads->contains($thread));
    }
}
