<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_fetch_their_last_reply()
    {
        $user = create(User::class);
        $reply = create(Reply::class, ['user_id' => $user->id]);
        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    /** @test */
    public function it_can_determine_its_avatar_path()
    {
        $user = create(User::class);

        $this->assertEquals(asset('images/avatars/default.png'), $user->avatar_path);

        $user->avatar_path = 'avatars/custom.jpg';

        $this->assertEquals(asset('storage/avatars/custom.jpg'), $user->avatar_path);
    }
}
