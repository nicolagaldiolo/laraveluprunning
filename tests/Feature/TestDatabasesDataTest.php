<?php

namespace Tests\Feature;

use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;

class TestDatabasesDataTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_active_page_shows_active_and_not_inactive_contacts()
    {
        $activeContact = factory(Contact::class)->create();
        $inactiveContact = factory(Contact::class, 'inactive')->create();

        $this->get('/eloquent-test/active-contacts')
            ->assertSee($activeContact->name)
            ->assertDontSee($inactiveContact->name);
    }

    public function test_event_creation_works()
    {
        $faker = Faker::create();
        $name = $faker->name;

        $this->post('eloquent-test/create-events', [
            'name' => $name
        ]);

        $this->assertDatabaseHas('events', [
            'name' => $name
        ]);
    }

    public function test_name_plus_mail_accessor_works()
    {
        $faker = Faker::create();
        $name = $faker->name;
        $email = $faker->email;

        $contact = factory(Contact::class)->create([
            'name' => $name,
            'email' => $email
        ]);

        $this->assertEquals(
            strtolower($name . ' - ' . $email),
            strtolower($contact->namePlusMail)
        );
    }

    public function test_vip_scope_filters_out_non_vips()
    {
        $vips = factory(Contact::class)->create([
            'status' => 10
        ]);
        $nonVips = factory(Contact::class)->create([
            'vips' => false
        ]);

        $contacts = Contact::vips()->get();
        $this->assertTrue($contacts->contains('id', $vips->id));
        $this->assertFalse($contacts->contains('id', $nonVips->id));
    }
}
