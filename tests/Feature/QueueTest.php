<?php

namespace Tests\Feature;

use App\Jobs\CrunchReports;
use App\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QueueTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        Queue::fake();

        Queue::assertNothingPushed();

        $user = factory(User::class)->create();
        CrunchReports::dispatch($user)->onQueue('test');

        Queue::assertPushedOn('test', CrunchReports::class);

        Queue::assertPushed(CrunchReports::class, function($job) use($user){
            return $job->user->id === $user->id;
        });

    }
}
