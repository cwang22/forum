<?php

namespace Tests\Feature;

use App\Activity;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_guest_cannot_create_new_thread()
    {
        $this->withExceptionHandling();
        $this->post('/threads', [])
            ->assertRedirect('/login');

        $this->get('/threads/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_thread()
    {
        $this->signIn();

        $thread = make(Thread::class);
        $response = $this->post('/threads', $thread->toArray());

        return $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        $this->publishThread(['channel_id' => 3])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function unauthenticated_user_cannot_delete_thread()
    {
        $this->withExceptionHandling();
        $thread = create(Thread::class);
        $this->delete($thread->path())
            ->assertRedirect(route('login'));

        $this->signIn();
        $this->delete($thread->path())
            ->assertStatus(403);
    }
    
    /** @test */
    public function authenticated_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = factory(User::class)->states('unconfirmed')->create();

        $this->signIn($user);
        $thread = make(Thread::class);
        return $this->post(route('threads'), $thread->toArray())->assertRedirect(route('threads'))
            ->assertSessionHas('flash');
    }

    /** @test */
    public function a_thread_can_be_deleted()
    {
        $this->signIn();
        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $this->delete($thread->path());
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, Activity::count());
    }

    /**
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();
        $thread = make(Thread::class, $overrides);
        return $this->post(route('threads'), $thread->toArray());
    }
}
