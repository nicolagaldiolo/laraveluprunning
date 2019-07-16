<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;
use App\Jobs\CrunchReports;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {

        Bus::fake();

        $user = factory(User::class)->create();
        Bus::dispatch(new CrunchReports($user));

        Bus::assertDispatched(CrunchReports::class, function ($job) use ($user){
            return $user->id === $job->user->id;
        });

    }
}
