<?php

namespace App\Http\Controllers;

use App\Thread;

class RepliesController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function store($channelId, Thread $thread)
    {
        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        return back()->with('flash', 'Reply has been created.');
    }
}
