<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Trending;

class SearchController extends Controller
{
    /**
     * Show the search results.
     *
     * @param Trending $trending
     * @return mixed
     */
    public function show(Trending $trending)
    {
        $threads = Thread::search(request('q'))->paginate(25);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }
}
