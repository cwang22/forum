<?php

namespace Tests\Feature;

use App\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChannelAdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function regular_user_cannot_create_channel()
    {
        $this->withExceptionHandling()
            ->signIn();
        $channel = make(Channel::class);

        $this->post(route('channels.store'), $channel->toArray())->assertStatus(403);
    }

    /** @test */
    public function admin_can_create_a_new_channel()
    {
        $this->signInAdmin();
        $channel = make(Channel::class);
        $this->post(route('channels.store'), $channel->toArray())->assertSuccessful();
    }

    /** @test */
    public function an_admin_can_update_a_channel()
    {
        $this->signInAdmin();
        $channel = create(Channel::class);
        $this->assertFalse($channel->archived);
        $this->patch(
            route('channels.update', $channel),
            [
                'name' => 'php',
                'archived' => true
            ]
        )->assertSuccessful();

        $this->assertEquals('php', $channel->fresh()->name);
        $this->assertTrue($channel->fresh()->archived);
    }
}
