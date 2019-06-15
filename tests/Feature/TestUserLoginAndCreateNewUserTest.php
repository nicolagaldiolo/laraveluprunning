<?php

namespace Tests\Feature;

use App\User;
use Faker\Factory as Faker;
use Illuminate\Contracts\Session\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestUserLoginAndCreateNewUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $user = factory(User::class)->create();
        $email = 'my@email.com';
        $this->be($user);

        $this->post('contact', [
            'email' => $email
        ]);

        $this->assertDatabaseHas('contacts', [
            'email' => $email,
            'user_id' => $user->id
        ]);

    }


    public function test_non_admins_cant_create_users()
    {
        $faker = Faker::create();

        $user = factory(User::class)->create([
            'admin' => false
        ]);

        $this->be($user);

        $dataToVerify = [
            'email' => $faker->email
        ];

        $this->post('users', $dataToVerify)
            ->assertForbidden();

        $this->assertDatabaseMissing('users', $dataToVerify);
    }


    public function test_admins_can_create_users()
    {
        $faker = Faker::create();

        $user = factory(User::class)->create([
            'admin' => true
        ]);

        $this->be($user);

        $dataToVerify = [
            'email' => $faker->email
        ];

        $this->post('users', $dataToVerify)
            ->assertOk();

        $this->assertDatabaseHas('users', $dataToVerify);
    }


    public function test_users_can_register()
    {
        $faker = Faker::create();

        $dataToVerify = [
            'name' => $faker->name,
            'email' => $faker->email,
        ];
        $otherData = [
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->post('register', array_merge($dataToVerify, $otherData));
        $this->followRedirects($response)->assertOk();

        $this->assertDatabaseHas('users', $dataToVerify);
    }

    public function test_users_can_login()
    {
        $password = 'password';
        $user = factory(User::class)->create([
            'password' => bcrypt($password)
        ]);

        $response = $this->post('login', [
            'email' => $user->email,
            'password' => $password
        ]);

        $this->followRedirects($response)->assertOk();
        $this->assertTrue(auth()->check());

    }
}
