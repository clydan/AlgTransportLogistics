<?php

namespace Database\Seeders;

use App\Models\Payload;
use App\Models\Route;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createRentalServices();

        $this->createPickupServices();

        $this->createDeliveryServices();
    }

    private function createRentalServices(): void
    {
        $serviceType = ServiceType::where('name', 'rental')->first();
        $userIds = User::role('customer')->pluck('id')->toArray();
        $vehicleId = 1;

        foreach ($userIds as $userId) {
            $numberOfWeeks = rand(1, 4);
            Service::create([
                'service_type_id' => $serviceType->id,
                'vehicle_id' => $vehicleId,
                'user_id' => $userId,
                'description' => fake()->sentence(),
                'status' => 'pending',
                'duration_in_hours' => $numberOfWeeks * 168,
                'estimated_cost' => 1000 * $numberOfWeeks,
            ]);
        }
    }

    private function createPickupServices(): void
    {
        $pickupCustomers = User::factory(123)->create();
        $serviceType = ServiceType::where('name', 'pickup')->first();
        foreach ($pickupCustomers as $customer) {
            $customer->assignRole('customer');
            
            $driver = User::factory()->create();
            
            $driver->assignRole('driver');

            $numberOfWeeks = rand(1, 4);

            $vehicle = Vehicle::create([
                'name' => fake()->word() . ' class',
                'manufacturer' => fake()->company(),
                'model' => fake()->word(),
                'class' => 'sedan',
                'registered_at' => now()->addDays(rand(1, 200)),
                'user_id' => $driver->id,
            ]);

            $service = Service::create([
                'service_type_id' => $serviceType->id,
                'vehicle_id' => $vehicle->id,
                'user_id' => $customer->id,
                'description' => fake()->sentence(),
                'status' => 'pending',
                'duration_in_hours' => $numberOfWeeks * 168,
                'estimated_cost' => 1000 * $numberOfWeeks,
            ]);

            $service->refresh();

            $route = Route::create([
                'service_id' => $service->id,
                'start_location' => fake()->address(),
                'end_location' => fake()->address(),
                'route' => [
                    'start_cordinate' => fake()->localCoordinates(),
                    'end_cordinate' => fake()->localCoordinates(),
                ]
            ]);

            $driver->update([
                'is_driver_available' => false,
            ]);

            $service->update([
                'route_id' => $route->id,
            ]);
        }
    }

    private function createDeliveryServices(): void
    {
        $deliveryCustomers = User::factory(302)->create();
        $serviceType = ServiceType::where('name', 'delivery')->first();
        foreach ($deliveryCustomers as $customer) {
            $customer->assignRole('customer');

            $driver = User::factory()->create();
            $driver->assignRole('driver');

            $numberOfWeeks = rand(1, 4);

            $vehicle = Vehicle::create([
                'name' => fake()->word() . ' class',
                'manufacturer' => fake()->company(),
                'model' => fake()->word(),
                'class' => 'pickup',
                'registered_at' => now()->addDays(rand(1, 200)),
                'user_id' => $driver->id,
            ]);

            $service = Service::create([
                'service_type_id' => $serviceType->id,
                'vehicle_id' => $vehicle->id,
                'user_id' => $customer->id,
                'description' => fake()->sentence(),
                'status' => 'pending',
                'duration_in_hours' => $numberOfWeeks * 168,
                'estimated_cost' => 1000 * $numberOfWeeks,
            ]);

            $service->refresh();

            $payload = Payload::create([
                'service_id' => $service->id,
                'tracking_number' => 'TN-ALG-' . rand(10000, 99999),
                'weight' => fake()->numberBetween(1, 100),
            ]);

            $route = Route::create([
                'service_id' => $service->id,
                'start_location' => fake()->address(),
                'end_location' => fake()->address(),
                'route' => [
                    'start_cordinate' => fake()->localCoordinates(),
                    'end_cordinate' => fake()->localCoordinates(),
                ]
            ]);

            $driver->update([
                'is_driver_available' => false,
            ]);

            $service->update([
                'route_id' => $route->id,
            ]);
        }
    }
}
