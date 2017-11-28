<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path', 'confirm_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'confirmed' => 'boolean'
    ];

    /**
     * Get the route key name for Laravel.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * A user has many threads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * A user has many activities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Confirm a user's email.
     */
    public function confirm()
    {
        $this->confirmed = true;
        $this->confirm_token = null;
        $this->save();
        return $this;
    }

    /**
     * Get the last reply of the user.
     * @return mixed
     */
    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    /**
     * Mark the thread as read.
     * @param Thread $thread
     * @return $this
     */
    public function read(Thread $thread)
    {
        cache()->forever(
            $this->visitedThreadCacheKey($thread),
            Carbon::now()
        );
        return $this;
    }

    /**
     * Get the cache key for when a user reads a thread.
     *
     * @param Thread $thread
     * @return string
     */
    public function visitedThreadCacheKey(Thread $thread)
    {
        return sprintf('users.%s.visits.%s', $this->id, $thread->id);
    }

    /**
     *  Get the avatar_path attribute
     *
     * @return string
     */
    public function getAvatarPathAttribute($avatar)
    {
        return asset($avatar ? 'storage/' . $avatar : 'images/avatars/default.png');
    }

    /**
     * Determine if the user is admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return in_array($this->email, ['i@seewang.me']);
    }
}
