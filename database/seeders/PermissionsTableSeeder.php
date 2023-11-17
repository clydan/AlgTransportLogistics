<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin',
            'customer',
            'driver',
        ];

        // TODO: Add permissions array later and link them to the roles 

        collect($roles)->each(function ($role) {
            Role::create(['name' => $role]);
        });
    }
}
