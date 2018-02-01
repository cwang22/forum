<?php

namespace App\Http\Controllers;

use App\Thread;

class PinnedThreadsController extends Controller
{
    /**
     * Pin the thread.
     *
     * @param Thread $thread
     */
    public function store(Thread $thread)
    {
        $thread->update(['pinned' => true]);
    }

    /**
     * Unpin the thread.
     *
     * @param Thread $thread
     */
    public function destroy(Thread $thread)
    {
        $thread->update(['pinned' => false]);
    }
}
