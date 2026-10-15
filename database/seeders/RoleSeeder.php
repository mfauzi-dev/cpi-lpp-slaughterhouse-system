<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Role::insert([
            [
                'name' => 'Admin',
            ],
            [
                'name' => 'General Manager',
            ],
            [
                'name' => 'Manager',
            ],
            [
                'name' => 'Admin Production',
            ],
            [
                'name' => 'Operator'
            ]
        ]);
    }
}
