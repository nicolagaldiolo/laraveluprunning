<?php

namespace App\Console\Commands;

use App\Mail\UserMailer;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class WelcomeNewUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:newusers';

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
        // manda una mail agli utenti registrati oggi
        User::where('created_at', '>=', Carbon::now()->startOfDay())->get()->each(function($user){
            Mail::to($user->email)->send(new UserMailer($user));
        });

    }
}
