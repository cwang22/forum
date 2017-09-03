<?php

namespace App;

use app\Filters\ThreadFilters;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordActivity;
    /**
     * mass assignment protections
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['owner', 'channel'];

    protected $appends = ['isSubscribed'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function (Thread $thread) {
            $thread->replies->each->delete();
        });
    }


    /**
     * a thread belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * a thread belongs to a channel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     *  Get a string path for the thread
     *
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/$this->id";
    }

    /**
     * Add a reply to the thread
     *
     * @param array $reply
     *
     * @return Model
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);
        $this->notifySubscriber($reply);
        return $reply;
    }

    /**
     * Notify all subscribers about a new reply
     *
     * @param Reply $reply
     */
    public function notifySubscriber($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
    }

    /**
     * a thread consists of replies
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Apply all relevant thread filters.
     * @param Builder $query
     * @param ThreadFilters $filters
     * @return Builder
     */
    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where([
                'user_id' => $userId ?: auth()->id()
        ])->delete();

        return $this;
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedAttribute()
    {
        return $this->subscriptions()->where(
            ['user_id' => auth()->id()]
        )->exists();
    }

    /**
     * @param $user
     * @return bool
     */
    public function hasUpdateForUser($user)
    {
        return cache()->get(auth()->user()->visitedThreadCacheKey($this)) < $this->updated_at;
    }
}
