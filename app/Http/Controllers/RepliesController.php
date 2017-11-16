<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Thread;
use Illuminate\Support\Facades\Gate;


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
     * @return \Illuminate\Http\Response | \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function store(Channel $channel, Thread $thread)
    {
        if (Gate::denies('create', new Reply)) {
            return response(
                'You are posting too frequently. Please take a break. :)', 429
            );
        }

        try {
            $this->validate(request(), ['body' => 'required|spamfree']);
            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this moment', 422);
        }

        return $reply->load('owner');

    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();

        if (request()->expectsJson()) {
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
        $this->validate(request(), ['body' => 'required|spamfree']);
        $reply->update(request(['body']));
    }
}
