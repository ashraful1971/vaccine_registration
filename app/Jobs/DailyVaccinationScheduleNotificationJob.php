<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\VaccinationScheduledNotification;
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
        User::whereDate('vaccine_scheduled_at', today()->addDay())
            ->chunk(1000, function ($users) {
                foreach ($users as $user) {
                    $user->notify(new VaccinationScheduledNotification);
                }
            });
    }
}
