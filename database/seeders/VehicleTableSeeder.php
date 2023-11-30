<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Testing\Fakes\Fake;

class VehicleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = [
            [
                'model' => 'Model S',
                'manufacturer' => 'Tesla',
                'class' => 'Electric'
            ],
            [
                'model' => 'Model 3',
                'manufacturer' => 'Tesla',
                'class' => 'Electric'
            ],
            [
                'model' => 'Model X',
                'manufacturer' => 'Tesla',
                'class' => 'Electric'
            ],
            [
                'model' => 'Model Y',
                'manufacturer' => 'Tesla',
                'class' => 'Electric'
            ],
            [
                'model' => 'Cybertruck',
                'manufacturer' => 'Tesla',
                'class' => 'Electric'
            ],
            [
                'model' => 'Mustang',
                'manufacturer' => 'Ford',
                'class' => 'Sport'
            ],
            [
                'model' => 'F-150',
                'manufacturer' => 'Ford',
                'class' => 'Truck'
            ],
            [
                'model' => 'Camry',
                'manufacturer' => 'Toyota',
                'class' => 'Sedan'
            ],
            [
                'model' => 'Corolla',
                'manufacturer' => 'Toyota',
                'class' => 'Sedan'
            ],
            [
                'model' => 'Civic',
                'manufacturer' => 'Honda',
                'class' => 'Sedan'
            ],
            // ... Repeat this structure 10 more times with different car models, manufacturers, and classes
        ];

        $id = $this->getDriverId();

        foreach ($cars as $car) {
            Vehicle::updateorCreate(
                [
                    'name' => Arr::get($car, 'manufacturer') . ' ' . Arr::get($car, 'model'),
                ],
                [
                    'user_id' => $id,
                    'name' => Arr::get($car, 'manufacturer') . ' ' . Arr::get($car, 'model'),
                    'manufacturer' => Arr::get($car, 'manufacturer'),
                    'class' => Arr::get($car, 'class'),
                    'model' => Arr::get($car, 'model'),
                    'registration_number' => $this->generateFakeRegistrationNumber(),
                    'registered_at' => now()->subDays(rand(1, 100)),
                    'is_available' => true
                ]
            );

            $id += 1;
        }

        for($i=0; $i<=20; $i++){
            Vehicle::create([
                'user_id' => $this->getDriverId(),
                'name' => 'Pickup ' . $i,
                'manufacturer' => 'Toyota',
                'class' => 'Pickup',
                'model' => 'Heavy Duty',
                'registration_number' => $this->generateFakeRegistrationNumber(),
                'registered_at' => now()->subDays(rand(1, 100)),
                'is_available' => true
            ]);
        }

        for($i=0; $i<=20; $i++){
            Vehicle::create([
                'user_id' => $this->getDriverId(),
                'name' => 'Sedan ' . $i,
                'manufacturer' => 'Toyota',
                'class' => 'Sedan',
                'model' => fake()->word(),
                'registration_number' => $this->generateFakeRegistrationNumber(),
                'registered_at' => now()->subDays(rand(1, 100)),
                'is_available' => true
            ]);
        }
    }

    private function getDriverId()
    {
        return User::role('driver')->inRandomOrder()->first()->id;
    }

    function generateFakeRegistrationNumber(): string
    {
        $letters = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);
        $numbers = rand(1000, 9999);

        return $letters . '-' . $numbers;
    }
}
