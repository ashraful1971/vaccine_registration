<?php

namespace App\Enums;

enum VaccinenationStatus: string
{
    case NOT_REGISTERED = 'not-registered';
    case NOT_SCHEDULED = 'not-scheduled';
    case SCHEDULED = 'scheduled';
    case VACCINATED = 'vaccinated';
}
