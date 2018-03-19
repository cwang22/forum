<?php

namespace App\Notifications;

use App\Reply;
use App\Thread;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ThreadWasUpdated extends Notification
{
    /**
     * @var Thread
     */
    protected $thread;

    /**
     * @var Reply
     */
    protected $reply;

    /**
     * ThreadWasUpdated constructor.
     * @param Thread $thread
     * @param Reply $reply
     */
    public function __construct(Thread $thread, Reply $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     * @return array
     */
    public function via()
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     * @return array
     */
    public function toArray()
    {
        return [
            'message' => $this->reply->owner->username . ' replied to ' . $this->thread->title,
            'link' => $this->reply->path()
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     *
     * @return BroadcastMessage
     */
    public function toBroadcast()
    {
        return new BroadcastMessage(['data' => $this->toArray()]);
    }
}
