<?php

namespace Database\Seeders;

use App\Models\Flights;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlightSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void {
        Flights::create([
            'flight_number' => 'VN-101',
            'from_code' => 'SGN',
            'to_code' => 'HAN',
            'departure_at' => now()->addDays(2)->setTime(8, 0),
            'price' => 2500000
        ]);
        Flights::create([
            'flight_number' => 'VN-107',
            'from_code' => 'SGN',
            'to_code' => 'HUI',
            'departure_at' => now()->addDays(2)->setTime(9, 0),
            'price' => 2500000
        ]);
        Flights::create([
            'flight_number' => 'VN-108',
            'from_code' => 'SGN',
            'to_code' => 'CXR',
            'departure_at' => now()->addDays(2)->setTime(10, 30),
            'price' => 2500000
        ]);
        Flights::create([
            'flight_number' => 'VN-108',
            'from_code' => 'SGN',
            'to_code' => 'HAN',
            'departure_at' => now()->addDays(2)->setTime(10, 30),
            'price' => 2500000
        ]);
        Flights::create([
            'flight_number' => 'VN-102',
            'from_code' => 'SGN',
            'to_code' => 'DLI',
            'departure_at' => now()->addDays(3)->setTime(8, 0),
            'price' => 1500000
        ]);
        Flights::create([
            'flight_number' => 'VN-103',
            'from_code' => 'SGN',
            'to_code' => 'DAD',
            'departure_at' => now()->addDays(4)->setTime(8, 0),
            'price' => 2000000
        ]);
        Flights::create([
            'flight_number' => 'VN-104',
            'from_code' => 'SGN',
            'to_code' => 'CXR',
            'departure_at' => now()->addDays(5)->setTime(8, 0),
            'price' => 1800000
        ]);
        Flights::create([
            'flight_number' => 'VN-109',
            'from_code' => 'SGN',
            'to_code' => 'CXR',
            'departure_at' => now()->addDays(5)->setTime(10, 0),
            'price' => 1800000
        ]);
        Flights::create([
            'flight_number' => 'VN-105',
            'from_code' => 'SGN',
            'to_code' => 'PQC',
            'departure_at' => now()->addDays(6)->setTime(8, 0),
            'price' => 2200000
        ]);
        Flights::create([
            'flight_number' => 'VN-106',
            'from_code' => 'SGN',
            'to_code' => 'HUI',
            'departure_at' => now()->addDays(7)->setTime(8, 0),
            'price' => 1700000
        ]);
    }
}
