<?php

namespace App\Console\Commands;

use App\Models\BusinessBooster\BusinessPlanApplication;
use App\Models\BusinessBooster\BusinessPlanInvoice;
use App\Models\BusinessBooster\BusinessPlanUserReliefDate;
use App\Models\InvoiceReminder;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class InvoiceReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'invoice reminder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        BusinessPlanApplication::with(['user', 'plan'])
                    ->where('status', 1)
                    ->get()
            ->each(function ($application) {

                $plan = $this->getApplicationPlan($application);

                $planDates = $this->getPlanDates($plan);

                $paidInvoicesDates = $this->getPaidInvoicesDates($application);

                $reliefDates = $this->getReliefDates($application);

                $todayDate = today()->format('d-m-Y');

                $defaulterDate = $this->calculateDefaulterDates($planDates, $paidInvoicesDates, $reliefDates, $todayDate);

                if ($defaulterDate != '') {
                    InvoiceReminder::create([
                        'user_id' => $application->applicant_id,
                        'reminder_date' => $defaulterDate
                    ]);
                }
            });
            \Log::info(today()->format('d-m-Y').' '.'invoice remineded');
    }

    public function getApplicationPlan($application)
    {
        return $application->plan;
    }

    public function getPlanDurationDates($plan): array
    {
        $startDate = date('Y-m-d', $plan->start_date);
        $endDate = date('Y-m-d', $plan->end_date);

        return array($startDate, $endDate);
    }

    public function getPlanDates($plan): array
    {
        $planDates = [];

        [$startDate, $endDate] = $this->getPlanDurationDates($plan);

        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $planDates[] = $date->format('d-m-Y');
        }

        return $planDates;
    }

    public function getPaidInvoicesDates($application): array
    {
        $paidInvoices = BusinessPlanInvoice::where('application_id', $application->id)->pluck('for_date')->toArray();
        $paidInvoices = array_map(function ($date) {
            return date('d-m-Y', $date);
        }, $paidInvoices);

        return $paidInvoices;
    }

    public function getReliefDates($application): array
    {
        $reliefDates = [];

        $reliefDateRecord = BusinessPlanUserReliefDate::where('application_id', $application->id)
            ->where('user_id', $application->applicant_id)
            ->first();

        if ($reliefDateRecord) {
            $reliefStartDate = date('Y-m-d', $reliefDateRecord->start_date);
            $reliefEndDate = date('Y-m-d', $reliefDateRecord->end_date);
            $reliefPeriod = CarbonPeriod::create($reliefStartDate, $reliefEndDate);

            foreach ($reliefPeriod as $reliefDatePeriod) {
                $reliefDates[] = $reliefDatePeriod->format('d-m-Y');
            }
        }

        return $reliefDates;
    }

    public function calculateDefaulterDates($planDates, $paidInvoicesDates, $reliefDates, $todayDate): string
    {
        $notPaidDate = '';

        foreach ($planDates as $planDate) {
            if ($planDate >= $todayDate && !in_array($planDate, $paidInvoicesDates) && !in_array($planDate, $reliefDates)) {
                $notPaidDate = $planDate;
                // -1 day
                $notPaidDate = date('d-m-Y', strtotime('-1 day', strtotime($notPaidDate)));
                break;
            }
        }

        return $notPaidDate;
    }

    public function alreadySent($application, $reminderDate)
    {
        return InvoiceReminder::where('user_id', $application->applicant_id)->where('reminder_date', $reminderDate)->exists();
    }
}
