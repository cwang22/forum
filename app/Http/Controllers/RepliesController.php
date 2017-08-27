<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Thread;
use Illuminate\Pagination\Paginator;

class RepliesController extends Controller
{
    function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * @param Channel $channel
     * @param Thread $thread
     * @return mixed
     */
    public function index(Channel $channel, Thread $thread)
    {
        return $thread->replies()->paginate(5);
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
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        if(request()->wantsJson()) {
            return $reply->load('owner');
        }

        return back()->with('flash', 'Reply has been created.');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();

        if(request()->expectsJson()) {
            return response()->json(['status' => 'Success']);
        }
        return back();
    }

    /**
     * Update existing reply.
     *
     * @param Reply $reply
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        $this->validate(request(), ['body' => 'required']);
        $reply->update(request(['body']));
    }
}
