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
        Commands\AggregatePortfolio::class,
        Commands\PeriodicOrder::class,
        Commands\BundleShipping::class,
        Commands\StepDirectMail::class,
        Commands\SendAutoMail::class,
        Commands\AutoMerge::class,
        Commands\CheckOrderData::class,
        Commands\CreateAspMediaCode::class,
        Commands\DisableAdmin::class,
        Commands\RegisterHolidays::class,
        Commands\MarketingSummary::class,
        Commands\PeriodicCountSummary::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //日次、月次の区別はせず、実施時刻順に記載。
        $schedule->command("batch:portfolio")->dailyAt("02:00")->name("AggregatePortfolio")->withoutOverlapping();
        $schedule->command("batch:create-asp-media-code")->monthlyOn("25", "03:05")->name("CreateAspMediaCode")->withoutOverlapping();
        $schedule->command("batch:auto-merge")->dailyAt("04:05")->name("AutoMerge")->withoutOverlapping();
        $schedule->command("batch:periodic-order")->dailyAt("06:00")->name("PeriodicOrder")->withoutOverlapping();
        $schedule->command("batch:bundle-shipping")->dailyAt("07:05")->name("BundleShipping")->withoutOverlapping();
        $schedule->command("batch:step-dm")->dailyAt("08:05")->name("StepDirectMail")->withoutOverlapping();
        $schedule->command("batch:check-order-data")->dailyAt("08:30")->name("CheckOrderData")->withoutOverlapping();
        $schedule->command("batch:send-auto-mail")->dailyAt("11:30")->name("SendAutoMail")->withoutOverlapping();
        $schedule->command("batch:disable-admin")->dailyAt("01:00")->name("DisableAdmin")->withoutOverlapping();
        $schedule->command("batch:register-holidays")->cron('0 0 30 11 *')->name("RegisterHolidays")->withoutOverlapping();
        $schedule->command("batch:marketing-summary")->monthlyOn("1", "03:05")->name("MarketingSummary")->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
