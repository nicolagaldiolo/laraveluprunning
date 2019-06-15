<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunMultipleCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'runcommand:all';

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
        // è possibile lanciare in maniera programmatica vari comandi
        // https://laravel.com/docs/5.8/artisan#calling-commands-from-other-commands

        $this->call('password:reset', [
            'userId' => 1, '--sendEmail' => 'true'
        ]);

        $this->callSilent('password:reset', [
            'userId' => 1, '--sendEmail' => 'true'
        ]);
    }
}
