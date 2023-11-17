<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rental = ServiceType::updateOrCreate([
            'name' => 'rental',
        ],[
            'name' => 'rental',
            'charge_configuration' => [
                'sedan' => 1000.00,
                'suv' => 1500.00,
                'luxury' => 2000.00,
            ]
        ]);
        $delivery = ServiceType::updateOrCreate([
            'name' => 'delivery',
        ],[
            'name' => 'delivery',
            'charge_configuration' => [
                '1km' => 10.00,
            ]
        ]);
        $pickup  = ServiceType::updateOrCreate([
            'name' => 'pickup',
        ],[
            'name' => 'pickup',
            'charge_configuration' => [
                '1km' => 20.00,
            ]
        ]);
    }
}
