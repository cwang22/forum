<?php
/**
 * Created by PhpStorm.
 * User: cwang
 * Date: 15/01/2018
 * Time: 10:06
 */

namespace App;


class Reputation
{
    const THREAD_PUBLISHED = 10;
    const REPLY_POSTED = 2;
    const BEST_REPLY = 50;

    public static function award($user, $points)
    {
        $user->increment('reputation', $points);
    }
}