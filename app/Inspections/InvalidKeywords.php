<?php
/**
 * Created by PhpStorm.
 * User: cwang
 * Date: 6/11/2017
 * Time: 13:44
 */

namespace App\Inspections;


class InvalidKeywords
{
    public function detect($body) {
        $invalidKeywords = [
            'yahoo customer support'
        ];

        foreach ($invalidKeywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new \Exception('Spam Detected');
            }
        }
    }
}