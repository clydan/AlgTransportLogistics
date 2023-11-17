<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
                    'registered_at' => now()->subDays(rand(1, 100)),
                ]
            );

            $id += 1;
        }
    }

    private function getDriverId()
    {
        return User::role('driver')->first()->id;
    }
}
