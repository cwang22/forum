<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_guest_may_not_create_new_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post('/threads', []);
    }

    /** @test */
    public function a_guest_cannot_see_create_thread_page()
    {
        $this->withExceptionHandling();
        $this->get('/threads/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_thread()
    {
        $this->signIn();

        $thread = make(Thread::class);

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
