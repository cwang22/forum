<?php

namespace Tests\Feature;

use App\Thread;
use App\Trending;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TrendingTest extends TestCase
{
    use DatabaseMigrations;

    protected $trending;

    protected function setUp()
    {
        parent::setUp();

        $this->trending = new Trending();

        $this->trending->reset();
    }

    /** @test */
    public function it_increments_a_score_each_time_a_thread_is_visited()
    {
        $this->assertEmpty($this->trending->get());

        $thread = create(Thread::class);
        $this->get($thread->path());

        $this->assertCount(1, $trending = $this->trending->get());
        $this->assertEquals($thread->title, $trending[0]->title);
    }

}
