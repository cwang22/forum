<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use app\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Thread extends Model
{
    use RecordActivity, Searchable;
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

    protected $casts = [
        'locked' => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function (Thread $thread) {
            $thread->update([
                'slug' => $thread->title
            ]);
        });

        static::deleting(function (Thread $thread) {
            $thread->replies->each->delete();
        });
    }


    /**
     * A thread belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A thread belongs to a channel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     *  Get a string path for the thread.
     *
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/$this->slug";
    }

    /**
     * Add a reply to the thread.
     *
     * @param array $reply
     *
     * @return Model
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

    /**
     * A thread consists of replies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Apply all relevant thread filters.
     *
     * @param Builder $query
     * @param ThreadFilters $filters
     * @return Builder
     */
    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Subscribe a user to the thread.
     *
     * @param null $userId
     * @return $this
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    /**
     * A thread can have many subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    /**
     * Unsubscribe a user from the thread.
     *
     * @param null $userId
     * @return $this
     */
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where([
            'user_id' => $userId ?: auth()->id()
        ])->delete();

        return $this;
    }

    /**
     * Determine if the current user is subscribed to the thread
     *
     * @return boolean
     */
    public function getIsSubscribedAttribute()
    {
        return $this->subscriptions()->where(
            ['user_id' => auth()->id()]
        )->exists();
    }

    /**
     * Determine if the thread has been updated since the user last read it.
     *
     * @param User $user
     * @return bool
     */
    public function hasUpdatesFor(User $user)
    {
        return cache()->get(auth()->user()->visitedThreadCacheKey($this)) < $this->updated_at;
    }

    /**
     * Get route key name for the thread.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Set the slug attribute
     *
     * @param $value
     */
    public function setSlugAttribute($value)
    {
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $slug = $this->uniqueSlug($slug);
        }

        $this->attributes['slug'] = $slug;
    }

    /**
     * Get an uniqueSlug
     *
     * @param $slug
     * @return string
     */
    protected function uniqueSlug($slug)
    {
        if (static::whereSlug($slug)->exists()) {
            $slug = "{$slug}-{$this->id}";
        }
        return $slug;
    }

    public function markBestReply(Reply $reply)
    {
        $this->best_reply_id = $reply->id;
        $this->save();

        return $this;
    }
}
