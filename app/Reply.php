<?php

namespace App;

use Carbon\Carbon;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordActivity;

    /**
     * Attributes that cannot be mass assigned.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['owner', 'favorites'];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['favoritesCount', 'isFavorited', 'isBest'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');

            Reputation::gain($reply->owner, Reputation::REPLY_POSTED);
        });

        static::deleted(function ($reply) {
            $reply->thread->decrement('replies_count');

            Reputation::lose($reply->owner, Reputation::REPLY_POSTED);
        });
    }

    /**
     * A reply belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A reply belongs to a thread.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Determine if the reply was just published a moment ago.
     *
     * @return bool
     */
    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subSeconds(30));
    }

    /**
     * users' names mentioned in reply body.
     * @return array
     */
    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        return $matches[1];
    }

    /**
     * Get the path to the reply.
     * @return string
     */
    public function path()
    {
        $limit = config('forum.pagination.reply');
        $count = $this->thread->replies()->pluck('id')->search($this->id) + 1;
        $page = ceil($count / $limit);

        return $this->thread->path()."?page=${page}#reply-{$this->id}";
    }

    /**
     * Set the body attribute.
     * @param string $body
     */
    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>', $body);
    }

    /**
     * Determine if the reply is marked as best reply.
     * @return bool
     */
    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    /**
     * Determine if the reply is marked as best reply.
     *
     * @return bool
     */
    public function isBest()
    {
        return $this->id == $this->thread->best_reply_id;
    }

    public function getBodyAttribute($body)
    {
        return Purify::clean($body);
    }
}
