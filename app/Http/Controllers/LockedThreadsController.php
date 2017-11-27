<?php

namespace App\Http\Controllers;

use App\Thread;

class LockedThreadsController extends Controller
{
    /**
     * Lock the thread.
     *
     * @param Thread $thread
     */
    public function store(Thread $thread)
    {
        $thread->update(['locked' => true]);

    }

    /**
     * Unlock the thread.
     *
     * @param Thread $thread
     */
    public function destroy(Thread $thread)
    {
        $thread->update(['locked' => false]);
    }
}