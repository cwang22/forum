<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PinThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_pin_a_thread()
    {
        $this->signInAdmin();
        $thread = create(Thread::class);
        $this->post(route('pinned-threads', $thread));
        $this->assertTrue($thread->fresh()->pinned);
    }

    /** @test */
    public function an_admin_can_unpin_a_thread()
    {
        $this->signInAdmin();
        $thread = create(Thread::class, ['pinned' => true]);
        $this->delete(route('pinned-threads', $thread));
        $this->assertFalse($thread->fresh()->pinned);
    }

    /** @test */
    public function other_users_cannot_pin_a_thread()
    {
        $this->withExceptionHandling()
            ->signIn();
        $thread = create(Thread::class);
        $this->post(route('pinned-threads', $thread))->assertStatus(403);
        $this->assertFalse($thread->fresh()->pinned);
    }

    /** @test */
    public function pinned_threads_are_list_first()
    {
        $this->signInAdmin();
        $threads = create(Thread::class, [], 3);
        $ids = $threads->pluck('id');

        $this->getJson(route('threads'))->assertJson([
            'data' => [
                ['id' => $ids[0]],
                ['id' => $ids[1]],
                ['id' => $ids[2]]
            ]
        ]);

        $this->post(route('pinned-threads', $pinned = $threads->last()));
        $this->getJson(route('threads'))->assertJson([
            'data' => [
                ['id' => $pinned->id],
                ['id' => $ids[0]],
                ['id' => $ids[1]]
            ]
        ]);
    }
}
