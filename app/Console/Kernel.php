<?php



namespace App\Console;



use App\Console\Commands\InvoiceReminderCommand;
use App\Console\Commands\SendPaymentNotificationToUser;
use App\Console\Commands\SendPrayerNotificationToUser;

use App\Console\Commands\UserSubscriptionCommand;

use Illuminate\Console\Scheduling\Schedule;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;



class Kernel extends ConsoleKernel

{

    protected $commands =[

        \App\Console\Commands\SendTrigeredEmails::class,

        InvoiceReminderCommand::class,

        UserSubscriptionCommand::class,

        SendPrayerNotificationToUser::class,
        SendPaymentNotificationToUser::class
    ];

    /**

     * Define the application's command schedule.

     *

     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule

     * @return void

     */

    protected function schedule(Schedule $schedule)

    {

//         $schedule->command('inspire')->everyMinute();

          $schedule->command('send:prayer-notification')->everyMinute();

          $schedule->command('send:payment-notification')->dailyAt('10:00');

          $schedule->command('invoice:reminder')->daily();

          $schedule->command('user:subscription')->daily();

          $schedule->command('send:mails')->everyMinute();

          $schedule->command('database:backup')->daily();

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

