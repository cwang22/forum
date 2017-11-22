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

    /**
     *
     *
     * @param Thread $threads
     */
    public function push(Thread $thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    public function cacheKey()
    {
        return app()->environment('testing') ? 'test_trending_thread' : 'trending_thread';
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }
}