<?php

namespace App\Console;

use App\Console\Commands\GenerarCargosDiarios;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        "App\Console\Commands\GenerarCargosDiarios",
        GenerarCargosDiarios::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        //$schedule->command('generar_cargos')->daily()->between('12:00', '05:00');
        $schedule->command('generar_cargos')->everyMinute();
    }

    /**
     * Register the commands for the application.
     * php artisan schedule:generar_cargos
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
