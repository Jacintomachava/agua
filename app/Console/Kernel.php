<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\LeituraJob;
use App\Jobs\EnviarSMS;
use App\Jobs\EnviarMensagemPeriodicaJob;
use App\Jobs\MensalidadeSistemaJob;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        // Preparar Leitura de Mes Anterior
        $schedule->job(new LeituraJob)->everyMinute(); //->monthlyOn(15, '00:00');
        //$schedule->job(new EnviarSMS)->everyMinute(); //->monthlyOn(15, '00:00');
        $schedule->job(new MensalidadeSistemaJob)->everyMinute(); //->monthlyOn(15, '00:00');
        $schedule->job(new EnviarMensagemPeriodicaJob)->everyMinute(); //->monthlyOn(15, '00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
