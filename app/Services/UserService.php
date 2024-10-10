<?php

namespace App\Services;

use App\Enums\VaccinenationStatus;
use App\Jobs\ScheduleVaccinationDateJob;
use App\Models\User;
use App\Models\VaccineCenter;
use App\Notifications\VaccinationScheduledNotification;
use Carbon\Carbon;

class UserService
{
    public static function register(array $data): bool
    {
        $user = User::create($data);
        ScheduleVaccinationDateJob::dispatch($user);

        return true;
    }
    
    public static function findByNid(string $nid): ?User
    {
        return User::where('nid', $nid)->first();
    }
    
    public static function scheduleVaccinationDate(User $user): void
    {
        $vaccineCenter = $user->vaccineCenter;
        $nextValidDate = self::getNextValidVaccinationDate($user->created_at);
        $latestVaccinationDate = User::where('vaccine_center_id', $vaccineCenter->id)
            ->whereDate('vaccine_scheduled_at', '>=', $nextValidDate)
            ->latest('id')
            ->value('vaccine_scheduled_at');

        if (! $latestVaccinationDate) {
            $user->vaccine_scheduled_at = $nextValidDate;
        } elseif (self::isAvailable($latestVaccinationDate, $vaccineCenter)) {
            $user->vaccine_scheduled_at = $latestVaccinationDate;
        } else {
            $user->vaccine_scheduled_at = self::getNextWeekDay($latestVaccinationDate);
        }

        $user->status = VaccinenationStatus::SCHEDULED;
        $user->save();
    }

    public static function sendDailyScheduleNotification(): void
    {
        User::whereDate('vaccine_scheduled_at', today()->addDay())
            ->chunk(1000, function ($users) {
                foreach ($users as $user) {
                    $user->notify(new VaccinationScheduledNotification());
                }
            });
    }

    private static function getNextValidVaccinationDate(Carbon $date): Carbon
    {

        if ($date->lt('21:00:00')) {
            return self::getNextWeekDay($date);
        }

        return self::getNextWeekDay($date->addDay());
    }

    private static function isAvailable(Carbon $date, VaccineCenter $vaccineCenter): bool
    {
        $appointmentCount = (int) User::where('vaccine_center_id', $vaccineCenter->id)
            ->whereDate('vaccine_scheduled_at', '=', $date)
            ->count();

        return $appointmentCount < (int) $vaccineCenter->daily_limit;
    }

    private static function getNextWeekDay(Carbon $date): Carbon
    {

        $nextDay = $date->addDay();

        if (! self::isWeekDay($nextDay)) {
            return $nextDay->startOfWeek(Carbon::SUNDAY)->addWeek();
        }

        return $nextDay;
    }

    private static function isWeekDay(Carbon $date): bool
    {

        return ! ($date->isFriday() || $date->isSaturday());
    }
}
