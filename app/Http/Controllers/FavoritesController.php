<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function store(Reply $reply)
    {
        $reply->favorite();

        return back();
    }
}
