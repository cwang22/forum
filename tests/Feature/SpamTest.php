<?php

namespace Tests\Feature;

use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /** @test */
    public function it_detects_spams()
    {
        $spam = new Spam();
        $this->assertFalse($spam->detect('Innocent content here'));

        $this->expectException(\Exception::class);
        $spam->detect('yahoo customer support');
    }
}
