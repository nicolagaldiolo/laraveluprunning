<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ExampleSeeder::class);
        $this->call(TraineeSeeder::class);
        $this->call(ReviewSeeder::class);
    }
}
