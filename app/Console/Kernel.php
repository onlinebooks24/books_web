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
        'App\Console\Commands\AmazonCategory',
        'App\Console\Commands\ArticleAlert',
        'App\Console\Commands\CollectMailFromGithub',
        'App\Console\Commands\CollectEmailAlert',
        'App\Console\Commands\SchedulerJobAlert',
        'App\Console\Commands\ThumbnailGenerate',
        'App\Console\Commands\SubscriptionMailSent'
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
        
        $schedule->command('backup:clean');
        $schedule->command('backup:run');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
