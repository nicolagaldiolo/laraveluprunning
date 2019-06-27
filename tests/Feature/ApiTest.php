<?php

namespace Tests\Feature;

use App\Dog;
use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api()
    {
        $dog1 = factory(Dog::class)->create();
        $dog2 = factory(Dog::class)->create();

        $user = factory(User::class)->create();
        $this->be($user, 'api');

        $this->get('api/testApiDogs')
            ->assertJsonFragment([
                'name' => $dog1->name,
            ])->assertJsonFragment([
                'name' => $dog2->name,
            ]);
    }
}
