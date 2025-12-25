<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Cek stok menipis dan barang kadaluarsa setiap hari jam 8 pagi
        $schedule->command('notifications:check-barang')->dailyAt('08:00');
        
        // Atau bisa juga setiap 6 jam sekali
        // $schedule->command('notifications:check-barang')->everySixHours();
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
