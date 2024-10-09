<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\VaccinenationStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'nid',
        'vaccine_center_id',
        'vaccine_scheduled_at',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'vaccine_scheduled_at' => 'datetime',
        ];
    }

    public function status(): Attribute
    {
        return Attribute::make(
            fn ($status) => $this->vaccine_scheduled_at != null && $this->vaccine_scheduled_at < today() ? VaccinenationStatus::VACCINATED->value : $status
        );
    }

    public function vaccineCenter(): BelongsTo
    {
        return $this->belongsTo(VaccineCenter::class);
    }
}
