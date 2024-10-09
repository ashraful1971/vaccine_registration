<?php

namespace App\Jobs;

use App\Enums\VaccinenationStatus;
use App\Models\User;
use App\Models\VaccineCenter;
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
        $vaccineCenter = $this->user->vaccineCenter;
        $nextValidDate = $this->getNextValidVaccinationDate($this->user->created_at);
        $latestVaccinationDate = User::where('vaccine_center_id', $vaccineCenter->id)
            ->whereDate('vaccine_scheduled_at', '>=', $nextValidDate)
            ->latest('id')
            ->value('vaccine_scheduled_at');

        if (! $latestVaccinationDate) {
            $this->user->vaccine_scheduled_at = $nextValidDate;
        } elseif ($this->isAvailable($latestVaccinationDate, $vaccineCenter)) {
            $this->user->vaccine_scheduled_at = $latestVaccinationDate;
        } else {
            $this->user->vaccine_scheduled_at = $this->getNextWeekDay($latestVaccinationDate);
        }

        $this->user->status = VaccinenationStatus::SCHEDULED;
        $this->user->save();
    }

    private function getNextValidVaccinationDate(Carbon $date): Carbon
    {

        if ($date->lt('21:00:00')) {
            return $this->getNextWeekDay($date);
        }

        return $this->getNextWeekDay($date->addDay());
    }

    private function isAvailable(Carbon $date, VaccineCenter $vaccineCenter): bool
    {
        $appointmentCount = (int) User::where('vaccine_center_id', $vaccineCenter->id)
            ->whereDate('vaccine_scheduled_at', '=', $date)
            ->count();

        return $appointmentCount < (int) $vaccineCenter->daily_limit;
    }

    private function getNextWeekDay(Carbon $date): Carbon
    {

        $nextDay = $date->addDay();

        if (! $this->isWeekDay($nextDay)) {
            return $nextDay->startOfWeek(Carbon::SUNDAY)->addWeek();
        }

        return $nextDay;
    }

    private function isWeekDay(Carbon $date): bool
    {

        return ! ($date->isFriday() || $date->isSaturday());
    }
}
