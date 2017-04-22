<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $fillable = ['user_id', 'title', 'body'];

    public function path()
    {
        return '/threads/' . $this->id;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }


    public function addReply($reply)
    {
        return $this->replies()->create($reply);
    }
}
