<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Area::create([
            'area_name'=> 'Desarrollo',
            'mañana_start_time'=>'08:00',
            'mañana_end_time'=>'14:00',
            'tarde_start_time'=>'15:00',
            'tarde_end_time'=>'21:00',
            'noche_start_time'=>'22:00',
            'noche_end_time'=>'04:00',
        ]);

        Area::create([
            'area_name'=> 'Marketing',
            'mañana_start_time'=>'09:00',
            'mañana_end_time'=>'15:00',
            'tarde_start_time'=>'16:00',
            'tarde_end_time'=>'22:00',
            'noche_start_time'=>'23:00',
            'noche_end_time'=>'05:00',
        ]);
    }
}
