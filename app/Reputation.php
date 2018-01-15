<?php
namespace App;


class Reputation
{
    const THREAD_PUBLISHED = 10;
    const REPLY_POSTED = 2;
    const BEST_REPLY = 50;

    public static function gain($user, $points)
    {
        $user->increment('reputation', $points);
    }

    public static function lose($user, $points)
    {
        $user->decrement('reputation', $points);
    }
}