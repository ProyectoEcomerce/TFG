<?php

namespace Database\Seeders;

use App\Models\Availability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Availability::create([
            'n_day'=>2,
            'avaibility'=>'tarde',
            'user_id'=>1,
            'week_id'=>1,
        ]);

        Availability::create([
            'n_day'=>2,
            'avaibility'=>'tarde',
            'user_id'=>2,
            'week_id'=>1,
        ]);
    }
}
