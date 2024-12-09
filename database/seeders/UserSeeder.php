<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VaccineCenter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(1)->create([
            'name' => 'Md. Ashraful',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('112233'),
        ]);

        User::factory(10)->create();
    }
}
