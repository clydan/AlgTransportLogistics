<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Route;
use App\Models\Payload;
use App\Models\Service;
use App\Models\Vehicle;
use App\Models\ServiceType;
use Illuminate\Console\Command;

class SimulateEntireSystemCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:simulate-entire-system-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command simulates the whole process of users putting in orders for a specific service type';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ServiceAtRandom = rand(1, 3);

        match ($ServiceAtRandom) {
            1 => $this->simulateRentalService(),
            2 => $this->simulatePickupService(),
            3 => $this->simulateDeliveryService(),
        };

        $this->handleTransactionStatusSimulation();
    }

    public function simulateRentalService(): void
    {
        $serviceTypeId = ServiceType::where('name', 'rental')->first()->id;
        $vehicleId = Vehicle::inRandomOrder()->where('is_available', true)->where('class', '!=', 'Pickup')->first()->id;
        $userId = User::inRandomOrder()->first()->id;

        $numberOfWeeks = rand(1, 4);

        Service::create([
            'service_type_id' => $serviceTypeId,
            'vehicle_id' => $vehicleId,
            'user_id' => $userId,
            'description' => fake()->sentence(),
            'status' => 'pending',
            'duration_in_hours' => $numberOfWeeks * 168,
            'estimated_cost' => 1000 * $numberOfWeeks,
        ]);
    }

    public function simulatePickupService(): void
    {
        $serviceTypeId = ServiceType::where('name', 'pickup')->first()->id;
        $vehicle = Vehicle::inRandomOrder()->where('is_available', true)->where('class', '!=', 'Pickup')->first();
        $userId = User::inRandomOrder()->first()->id;
        $numberOfKm = rand(1, 10);

        $service = Service::create([
            'service_type_id' => $serviceTypeId,
            'vehicle_id' => $vehicle->id,
            'user_id' => $userId,
            'description' => fake()->sentence(),
            'status' => 'pending',
            'duration_in_hours' => rand(1, 5),
            'estimated_cost' => 50 * $numberOfKm,
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

        $service->update([
            'route_id' => $route->id,
        ]);
    }

    public function simulateDeliveryService(): void
    {
        $serviceType = ServiceType::where('name', 'pickup')->first();
        $vehicleId = Vehicle::inRandomOrder()->where('is_available', true)->where('class', 'Pickup')->first()->id;
        $userId = User::inRandomOrder()->first()->id;
        $numberOfKm = rand(1, 10);

        $service = Service::create([
            'service_type_id' => $serviceType->id,
            'vehicle_id' => $vehicleId,
            'user_id' => $userId,
            'description' => fake()->sentence(),
            'status' => 'pending',
            'duration_in_hours' => rand(1, 20),
            'estimated_cost' => 1000 * $numberOfKm,
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

        $service->update([
            'route_id' => $route->id,
        ]);
    }

    private function handleTransactionStatusSimulation()
    {
        $service = Service::where('status', 'pending')->first();

        $randomNumber = rand(1, 10);

        $service->update([
            'status' => $randomNumber % 2 == 0 ? 'completed' : 'cancelled',
        ]);
        
        $service->vehicle->update([
            'is_available' => true,
        ]);
        
        $service->vehicle->user->update([
            'is_driver_available' => true,
        ]);
    }
}
