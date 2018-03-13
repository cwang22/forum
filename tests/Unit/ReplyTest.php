<?php

namespace Tests\Unit;

use App\Reply;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_an_owner()
    {
        $reply = create(Reply::class);
        $this->assertInstanceOf(User::class, $reply->owner);
    }

    /** @test */
    public function it_knows_if_it_was_just_published()
    {
        $reply = create(Reply::class);
        $this->assertTrue($reply->wasJustPublished());
        $reply->created_at = Carbon::now()->subMonth();
        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function it_can_detect_all_mentioned_users()
    {
        $reply = create(Reply::class, ['body' => '@JohnDoe mentioned @JaneDoe']);

        $this->assertEquals(['JohnDoe', 'JaneDoe'], $reply->mentionedUsers());
    }

    /** @test */
    public function it_wraps_mentioned_users_in_body()
    {
        $reply = create(Reply::class, ['body' => 'Hello @JohnDoe']);

        $this->assertEquals('Hello <a href="/profiles/JohnDoe">@JohnDoe</a>', $reply->body);
    }

    /** @test */
    public function a_reply_body_is_sanitized_automatically()
    {
        $thread = make(Reply::class, ['body' => '<script>alert("hi")</script><p>this is good</p>']);
        $this->assertEquals('<p>this is good</p>', $thread->body);
    }

    /** @test */
    public function it_generates_correct_paginated_path()
    {
        $thread = create(Thread::class);

        $replies = create(Reply::class, ['thread_id' => $thread->id], 3);

        config(['forum.pagination.reply' => 1]);

        $this->assertEquals(
            $thread->path() . '?page=3#reply-3',
            $replies->last()->path()
        );
    }
}
