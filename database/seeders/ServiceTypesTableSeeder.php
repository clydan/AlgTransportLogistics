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
            'charge_per_km' => 100
        ]);
        $delivery = ServiceType::updateOrCreate([
            'name' => 'delivery',
        ],[
            'name' => 'delivery',
            'charge_per_km' => 20
        ]);
        $pickup  = ServiceType::updateOrCreate([
            'name' => 'pickup',
        ],[
            'name' => 'pickup',
            'charge_per_km' => 50
        ]);
    }
}
