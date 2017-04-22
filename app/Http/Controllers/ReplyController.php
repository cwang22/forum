<?php

namespace App\Http\Controllers;

use App\Thread;

class ReplyController extends Controller
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

        return back();
    }
}
