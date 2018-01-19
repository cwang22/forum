<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    /**
     * Get the top 5 popular threads.
     *
     * @return array
     */
    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    public function cacheKey()
    {
        return app()->environment('testing') ? 'test_trending_thread' : 'trending_thread';
    }

    /**
     * Push the thread up in trending list.
     *
     * @param Thread $thread
     */
    public function push(Thread $thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    /**
     * Reset cache.
     */
    public function reset()
    {
        Redis::del($this->cacheKey());
    }
}
