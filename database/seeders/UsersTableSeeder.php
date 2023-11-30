<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
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

        collect($roles)->each(function ($role) {
            for ($i = 0; $i < $this->setNumberOfUser($role); $i++) {
                $user = User::factory()->create();
                $user->assignRole($role);
            }
        });

        User::create([
            'name' => 'main admin',
            'email' => 'dansoclyde@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }

    private function setNumberOfUser($role){
        return match($role){
            'admin' => 1,
            'customer' => 50,
            'driver' => 10,
        };
    }
}
