<?php

namespace App\Events;

use App\Reply;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThreadReceivedNewReply
{
    use Dispatchable, SerializesModels;

    /** @var  Reply */
    public $reply;

    /**
     * ThreadReceivedNewReply constructor.
     * @param Reply $reply
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }


}
