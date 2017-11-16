<?php

namespace App\Inspections;


class Spam
{
    /**
     * All registered inspections.
     *
     * @var array
     */
    protected $inspections = [
        InvalidKeywords::class,
        KeyHoldDown::class
    ];

    /**
     * Detect spam.
     *
     * @param  string $body
     * @return bool
     */
    public function detect($body)
    {
        foreach($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }
        return false;
    }

}