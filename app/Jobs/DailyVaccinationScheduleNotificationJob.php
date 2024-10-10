<?php

namespace App\Jobs;

use App\Services\UserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DailyVaccinationScheduleNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        UserService::sendDailyScheduleNotification();
    }
}
