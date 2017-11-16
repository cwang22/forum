<?php

namespace App\Inspections;


class KeyHoldDown
{
    /**
     * Detect spam.
     *
     * @param  string $body
     * @throws \Exception
     */
    public function detect($body)
    {
        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new \Exception('Spam Detected');
        }
    }
}