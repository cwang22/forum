<?php

namespace App\Http\Controllers;

use App\Channel;
use Illuminate\Validation\Rule;

class ChannelsController extends Controller
{
    /**
     * Create a new channel.
     *
     * @return Channel
     * @throws \Exception
     */
    public function store()
    {
        $channel = Channel::create(
            request()->validate([
                'name' => 'required|unique:channels'
            ])
        );

        cache()->forget('channels');

        return $channel;
    }

    /**
     * Update an existing channel.
     *
     * @param Channel $channel
     * @return Channel
     * @throws \Exception
     */
    public function update(Channel $channel)
    {
        $channel->update(
            request()->validate([
                'name' => ['required', Rule::unique('channels')->ignore($channel->id)],
                'archived' => 'boolean'
            ])
        );

        cache()->forget('channels');

        return $channel;
    }
}
