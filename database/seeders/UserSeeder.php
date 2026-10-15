<?php

namespace Database\Seeders;

use App\Models\CostCenter;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $generalManagerRole = Role::where('name', 'General Manager')->first();
        $adminProductionRole = Role::where('name', 'Admin Production')->first();
        $operatorRole = Role::where('name', 'Operator')->first();
        $managerRole = Role::where('name', 'Manager')->first();

        User::insert([
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'General Manager',
                'email' => 'generalmanager@mail.com',
                'password' => Hash::make('password'),
                'role_id' => $generalManagerRole->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Slaughter House',
                'email' => 'slaughterhouse@mail.com',
                'password' => Hash::make('password'),
                'role_id' => $adminProductionRole->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Operator Slaughter House',
                'email' => 'operatorslaughterhouse@mail.com',
                'password' => Hash::make('password'),
                'role_id' => $operatorRole->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'name' => 'Mohammad Fauzi',
                'email' => 'managerslaughterhouse@mail.com',
                'password' => Hash::make('password'),
                'role_id' => $managerRole->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
