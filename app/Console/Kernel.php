<?php

namespace App\Console;

use App\Jobs\order\AutoFinishOrder;
use App\Jobs\order\ClearExpiredOrders;
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
        //
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

        // Order
        $schedule->job(ClearExpiredOrders::class)->everyMinute();
        $schedule->job(AutoFinishOrder::class)->hourly();

        // 书籍借阅
        $schedule->command('blacklist:scanner')
            ->description('检查借书超过最长期限未还的用户并禁用')
            ->dailyAt('20:00');
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
