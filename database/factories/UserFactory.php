<?php

namespace Database\Factories;

use App\Jobs\ScheduleVaccinationDateJob;
use App\Models\VaccineCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'nid' => fake()->numberBetween(100000000, 999999999),
            'vaccine_center_id' => VaccineCenter::select('id')->inRandomOrder()->first()->id,
            'vaccine_scheduled_at' => null,
            'status' => 'not-scheduled',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function configure()
    {
        return $this->afterCreating(function ($user) {
            ScheduleVaccinationDateJob::dispatchSync($user);
        });
    }
}
