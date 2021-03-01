<?php

namespace App\Console;

use App\Models\CryptoPrice;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\StoreCryptoPrice::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('crypto:store-price')->everyTenMinutes();

        $schedule->call(fn () => CryptoPrice::whereDate('created_at', '<', Carbon::now()->subDays(90)->toDateString())->delete())->daily();
    }
}
