<?php

namespace App\Console;

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
        // $schedule->command('inspire')
        //          ->hourly();
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
