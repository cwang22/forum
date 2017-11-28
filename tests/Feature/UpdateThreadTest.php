<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_thread_can_be_updated()
    {
        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $this->patch($thread->path(), [
            'title' => 'new title',
            'body' => 'new body'
        ]);

        $this->assertEquals($thread->fresh()->title, 'new title');
        $this->assertEquals($thread->fresh()->body, 'new body');
    }

    /** @test */
    public function updated_thread_must_pass_validation()
    {
        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $this->patch($thread->path(), [
            'title' => 'new title'
        ])->assertSessionHasErrors('body');
    }

    /** @test */
    public function unauthorized_user_cannot_update_threads()
    {
        $thread = create(Thread::class);
        $this->patch($thread->path(), [
            'title' => 'new title',
            'body' => 'new body'
        ])->assertStatus(403);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->withExceptionHandling()->signIn();
    }

}
