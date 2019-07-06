<?php

namespace Tests\Feature;

use App\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\TestCase;

class StorageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_storage()
    {

        // Genero un istanza Uploaded file e la inietto nella rotta assicurandomi che non vengano sollevati errori
        $path = storage_path('app/public/tests') . '/test.png';

        $file = new \Illuminate\Http\UploadedFile(
            $path,
            'test.png',
            'image/png',
            null,
            true
        );

        $response = $this->call('post', 'upload-route', [], [], ['picture' => $file]);
        $response->assertOk();

    }

    public function test_user_profile()
    {

        $user = factory(User::class)->create();

        $response = $this->get('users/' . $user->id);
        $response->assertSee($user->picture)
            ->assertJsonFragment(['picture' => $user->picture]);


    }
}

?>