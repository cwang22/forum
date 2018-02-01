<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $john = create(User::class, ['name' => 'JohnDoe']);

        $this->signIn($john);

        $jane = create(User::class, ['name' => 'JaneDoe']);

        $thread = create(Thread::class);

        $reply = make(Reply::class, [
            'body' => '@JaneDoe check this out'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->notifications);

    }

    /** @test */
    public function it_can_fetch_all_users_starting_with_given_characters()
    {
        create(User::class, ['name' => 'JohnDoe']);
        create(User::class, ['name' => 'JaneDoe']);
        create(User::class, ['name' => 'JohnDoe2']);

        $result = $this->json('GET', 'api/users', ['name' => 'John']);

        $this->assertCount(2, $result->json());
    }

}
