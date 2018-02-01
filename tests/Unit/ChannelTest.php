<?php

namespace Tests\Feature;

use App\Channel;
use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_threads()
    {
        $channel = create(Channel::class);
        $thread = create(Thread::class, ['channel_id' => $channel->id]);
        $this->assertTrue($channel->threads->contains($thread));
    }
    
    /** @test */
    public function it_can_be_archived()
    {
        $channel = create(Channel::class);
        $this->assertFalse($channel->archived);
    }
}
