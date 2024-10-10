<?php

namespace App\Services;

use App\Models\VaccineCenter;

class VaccineCenterService
{
    public static function all(): array
    {
        return VaccineCenter::all(['id', 'name'])->pluck('name', 'id')->toArray();
    }
}
