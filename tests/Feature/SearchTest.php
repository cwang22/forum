<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_user_can_search_threads()
    {
        if ( ! config('scout.algolia.id')) {
            $this->markTestSkipped("Algolia is not configured.");
        }

        config(['scout.driver' => 'algolia']);

        create(Thread::class, [], 2);
        create(Thread::class, ['body' => "A thread with foobar term"], 2);

        do {
            // Account for latency.
            sleep(.25);

            $results = $this->getJson('/threads/search?q=foobar')->json()['data'];
        } while (empty($results));

        $this->assertCount(2, $results);

        Thread::latest()->take(4)->unsearchable();
    }
}
