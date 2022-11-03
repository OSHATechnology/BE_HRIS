<?php

namespace App\Console;

use App\Models\Salary;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('demo:cron')->everyMinute()->sendOutputTo(storage_path('logs/cron.log'));
        // $schedule->command('salary:cron')->everyMinute()->sendOutputTo(storage_path('logs/cron.log'));
        // schedule call controller
        $schedule->call(function () {
            $request = new Request(['month' => '10-2022']);
            $controller = app()->make('App\Http\Controllers\SalaryController');
            $controller->generateSalary($request);
        })->monthlyOn(Salary::PAYROLLDATE, '00:10')->sendOutputTo(storage_path('logs/cron.log'));
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
