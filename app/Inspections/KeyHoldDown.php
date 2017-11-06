<?php
/**
 * Created by PhpStorm.
 * User: cwang
 * Date: 6/11/2017
 * Time: 13:48
 */

namespace App\Inspections;


class KeyHoldDown
{
    public function detect($body) {
        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new \Exception('Spam Detected');
        }
    }
}