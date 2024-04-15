<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'manuelsr0113@gmail.com',
            'password' => bcrypt('12345678'),
            'area_id' => 2
        ]);

        $adminRole = Role::where('role', 'admin')->first();

        $user->roles()->attach($adminRole->id);

        $user = User::create([
            'name' => 'Luis',
            'email' => 'lyergamayor1@gmail.com',
            'password' => bcrypt('p12345678'),
            'area_id' => 1
        ]);

        $userRole = Role::where('role', 'user')->first();
        $user = User::create([
            'name' => 'Nicolás',
            'email' => 'nicogomezgarcia1@gmail.com',
            'password' => bcrypt('11111111'),
            'area_id' => 1
        ]);

        $userRole = Role::where('role', 'user')->first();
        if ($userRole) {
            $user->roles()->attach($userRole->id);
        }
    }
}
