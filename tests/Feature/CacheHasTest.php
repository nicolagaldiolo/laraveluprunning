<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheHasTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_cache_has()
    {
        //logger("CACHE_DRIVER: " . env('CACHE_DRIVER'));

        $key = 'testKey';
        $value = 1000;
        Cache::put($key, $value, 15);

        $this->assertEquals($value, Cache::get($key));
    }
}
