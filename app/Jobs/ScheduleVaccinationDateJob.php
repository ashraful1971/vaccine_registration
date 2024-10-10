<?php

namespace App\Jobs;

use App\Enums\VaccinenationStatus;
use App\Models\User;
use App\Models\VaccineCenter;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ScheduleVaccinationDateJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        UserService::scheduleVaccinationDate($this->user);
    }
}
