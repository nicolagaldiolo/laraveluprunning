<?php

use Illuminate\Database\Seeder;

class ExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Task::class, 'task_with_desc', 20)->create();
        $users = factory(App\User::class, 20)->create();



        factory(App\Friend::class, 30)->create([
            'user_id' => $users->pluck('id')->random()
        ]);
    }
}
