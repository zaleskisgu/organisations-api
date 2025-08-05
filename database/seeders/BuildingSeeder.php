<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        Building::create([
            'address' => 'г. Москва, ул. Ленина 1, офис 3',
            'latitude' => 55.7558,
            'longitude' => 37.6176,
        ]);

        Building::create([
            'address' => 'г. Москва, ул. Тверская 10, офис 15',
            'latitude' => 55.7585,
            'longitude' => 37.6011,
        ]);

        Building::create([
            'address' => 'г. Москва, ул. Арбат 25',
            'latitude' => 55.7494,
            'longitude' => 37.5931,
        ]);

        Building::create([
            'address' => 'г. Москва, ул. Новый Арбат 15, офис 7',
            'latitude' => 55.7500,
            'longitude' => 37.5700,
        ]);

        Building::create([
            'address' => 'г. Москва, ул. Блюхера 32/1',
            'latitude' => 55.7600,
            'longitude' => 37.5800,
        ]);
    }
} 