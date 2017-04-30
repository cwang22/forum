<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Thread;

class RepliesController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Channel $channel
     * @param Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function store(Channel $channel, Thread $thread)
    {
        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        return back()->with('flash', 'Reply has been created.');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();
        return back();
    }
}
