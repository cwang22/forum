<?php

namespace App\Notifications;

use App\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class YouWereMentioned extends Notification
{
    use Queueable;

    /** @var Reply */
    protected $reply;

    /**
     * YouWereMentioned constructor.
     * @param Reply $reply
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'message' => $this->reply->owner->name.' mentioned you in '.$this->reply->thread->title,
            'link' => $this->reply->thread->path()
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
