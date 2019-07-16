<?php

namespace App\Console;

use App\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/*
 * REGISTRARE I COMMANDS
 * Per registrare i commands so può procedere in 2 modi:
 * - registrare i singoli command nell'array commands[]
 * - chiamare il metodo loas nel metodo commands passando il path della cartella per caricare tutti i commands presenti
 *      al suo interno in botta unica (è possibile quindi caricarli anche da cartelle differenti)
 */

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\WelcomeNewUsers::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*
         * DEFINISCO LA SCHEDULAZIONE DEI TASK
         * Esistono 3 tipi di task:
         *
         */

        // 1- CLOUSURE
        $schedule->call(function(){
            $user = User::first();
            dispatch(new \App\Jobs\CrunchReports($user));
        })->everyTenMinutes();

        // 2- ARTISAN COMMAND
        $schedule->command('password:reset userId:5 --sendEmail')
            ->everyTenMinutes();

        // 3- PHP EXEC() METHOD
        $schedule->exec('/home/myapp.com/build.sh')
            ->everyTenMinutes();

        // per i tempi di schedulazione
        // https://laravel.com/docs/5.8/scheduling#schedule-frequency-options


        // OVERLAPPING

        // Questo task viene lanciato ogni minuto, ma se la sua elaborazione dura più di un minuto non avviene sovrapposizione, quindi non
        // viene rilanciato se l'esecuzione del precedente non è ancora finita
        //$schedule->call(function(){ })->everyTenMinutes()->withoutOverlapping();

        // Questo task viene lanciato ogni minuto, ma se la sua elaborazione dura più di un minuto non avviene sovrapposizione, quindi non
        // viene rilanciato se l'esecuzione del precedente non è ancora finita ma solo fino a 10 minuti, dopo di chè il task viene comunque
        // rilanciato
        //$schedule->call(function(){ })->everyTenMinutes()->withoutOverlapping(10);

        // GESTIONE OUTPUT (può essere lanciato solo con command o exec, non con le clousure)
        // ciò che viene tornato da ciascun task può essere salvato su file
        // può essere appeso ad un file
        // può essere inviato per mail (prima deve essere scitto su file e dopo inviato per mail, altrimenti viene
        // prima creato un file di log e poi il suo contenuto inviato per mail)

        $schedule->command('password:reset userId:5 --sendEmail')
            ->everyMinute()
            ->sendOutputTo(storage_path('app/public/report.txt'));

        $schedule->command('password:reset userId:5 --sendEmail')
            ->everyMinute()
            ->appendOutputTo(storage_path('app/public/report.txt'));

        $schedule->command('password:reset userId:5 --sendEmail')
            ->everyMinute()
            //->sendOutputTo(storage_path('app/public/report.txt'))
            ->emailOutputTo('me@myapp.com') // chiamato ogni volta
            ->emailOutputOnFailure('foo@example.com'); // chiamato quando il comando fallisce

        // TASK HOOKS
        // https://laravel.com/docs/5.8/scheduling#task-hooks
        $url = '';
        $schedule->command('password:reset userId:5 --sendEmail')
            ->everyMinute()
            ->before(function(){
                // viene chiamato prima di eseguire il task
            })
            ->after(function(){
                // viene chiamato dopo aver eseguito il task
            })
            ->onSuccess(function () {
                // The task succeeded...
            })
            ->onFailure(function () {
                // The task failed...
            })
            ->pingBefore($url)
            ->thenPing($url)
            ->pingBeforeIf(true, $url)
            ->thenPingIf(true, $url)
            ->pingOnSuccess($url)
            ->pingOnFailure($url);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
