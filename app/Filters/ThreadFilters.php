<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filters
{
    /**
     * Registered filters
     *
     * @var array
     */
    protected $filters = ['by'];

    /**
     * Filter query by username
     *
     * @param $username
     */
    public function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        $this->builder->where('user_id', $user->id);
    }

}