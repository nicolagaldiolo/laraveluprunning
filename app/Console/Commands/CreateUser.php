<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $name = $this->ask("What your Name?");
        $surname = $this->ask("What your Surname?");
        $email = $this->ask("What your Email?");
        $password = $this->secret("Select a password?");
        $album = $this->anticipate('What is the best album ever? (The Joshua Tree)', [
            "The Joshua Tree", "Pet Sounds", "What's Going On"
        ]);
        $role = $this->choice('What role do you want?', ['User', 'Admin'], 0);


        if($this->confirm("Do you want to view your data?")){
            $this->line('Name: ' . $name);
            $this->line('Surname: ' . $surname);
            $this->line('Email: ' . $email);
            $this->line('Password: ' . $password);
            $this->line('Album: ' . $album);
            $this->line('Role: ' . $role);

            $this->info('This is an info');
            $this->comment('This is a comment');
            $this->question('This is a question');
            $this->error('This is an error');
        }


        if($this->confirm("Do you want view everyUser?")){
            $headers = ['Name', 'Email'];
            $data = [
                ['Utente uno', 'user@user.com'],
                ['Utente due', 'user2@user.com']
            ];

            $data = User::all(['name', 'email'])->toArray();

            $this->table($headers, $data);
        }

        $totalUnits = 10;
        $this->output->progressStart($totalUnits);

        for ($i=0; $i<$totalUnits; $i++){
            sleep(1);
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();


    }
}
