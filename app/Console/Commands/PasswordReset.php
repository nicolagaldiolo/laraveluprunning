<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PasswordReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:reset {userId : The ID of the user} {--sendEmail : Whether to send user an email}';

    //{userId}                      argomento obbligatorio
    //{userId?}                     argomento facoltativo
    //{userId=1}                    argomento facoltativo con valore di default
    //{userId*}                     argomento è un array
    //{userId?*}                    argomento è un array favoltativo altrimenti [] *** ARRAY SU ARGOMENTI DEVE SEMPRE ESSERE L'ULTIMO PARAMETRO
    //{userId : description}        identifica la descrizione che appare quando lanci il comando --help

    // Le opzioni sono sempre facoltative

    //{--sendEmail}                 identifica un opzione (true/false)
    //{--sendEmail=}                opzione che può avere un valore
    //{--sendEmail=default}         opzione con un valore di default
    //{--sendEmail=*}               opzione che può è un array
    //{--sendEmail : description}   identifica la descrizione che appare quando lanci il comando --help

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

        //$this->argument()
        //$this->argument('userId')
        //$this->option()
        //$this->option('sendEmail')

        $this->line('userId: ' . $this->argument('userId'));
        $this->line('sendEmail: ' . $this->option('sendEmail'));

    }
}
