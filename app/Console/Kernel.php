<?php

namespace App\Console;

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
        'App\Console\Commands\CronEmail',
        'App\Console\Commands\DatabaseBackupCommand',
        'App\Console\Commands\ImportCsvFiles',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('notify:email')
//            ->timezone('Asia/Dhaka')
//            ->dailyAt('16:00');
//
//        $schedule->command('backup:database')
//            ->timezone('Asia/Dhaka')
//            ->weeklyOn(1, '14:00');

        $schedule->command('make:csv-import')
            ->timezone('Asia/Dhaka')
            ->everyTenMinutes();
    }
}
