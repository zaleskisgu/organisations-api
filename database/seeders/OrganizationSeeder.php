<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use App\Models\OrganizationPhone;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $buildings = Building::all();
        $activities = Activity::all();

        // ООО "Рога и Копыта" - мясная продукция
        $org1 = Organization::create([
            'name' => 'ООО "Рога и Копыта"',
            'building_id' => $buildings->first()->id,
        ]);

        $org1->phones()->createMany([
            ['phone' => '2-222-222'],
            ['phone' => '3-333-333'],
            ['phone' => '8-923-666-13-13'],
        ]);

        $org1->activities()->attach($activities->where('name', 'Мясная продукция')->first());

        // Молочный завод - молочная продукция
        $org2 = Organization::create([
            'name' => 'Молочный завод "Свежесть"',
            'building_id' => $buildings->get(1)->id,
        ]);

        $org2->phones()->createMany([
            ['phone' => '4-444-444'],
            ['phone' => '8-800-555-35-35'],
        ]);

        $org2->activities()->attach($activities->where('name', 'Молочная продукция')->first());

        // Автосервис - грузовые автомобили
        $org3 = Organization::create([
            'name' => 'Автосервис "Грузовик"',
            'building_id' => $buildings->get(2)->id,
        ]);

        $org3->phones()->createMany([
            ['phone' => '5-555-555'],
        ]);

        $org3->activities()->attach($activities->where('name', 'Грузовые')->first());

        // Пекарня - хлебобулочные изделия
        $org4 = Organization::create([
            'name' => 'Пекарня "Свежий хлеб"',
            'building_id' => $buildings->get(3)->id,
        ]);

        $org4->phones()->createMany([
            ['phone' => '6-666-666'],
            ['phone' => '8-999-123-45-67'],
        ]);

        $org4->activities()->attach($activities->where('name', 'Хлебобулочные изделия')->first());

        // Консультационная фирма - услуги
        $org5 = Organization::create([
            'name' => 'Консультационная фирма "Эксперт"',
            'building_id' => $buildings->get(4)->id,
        ]);

        $org5->phones()->createMany([
            ['phone' => '7-777-777'],
        ]);

        $org5->activities()->attach($activities->where('name', 'Консультации')->first());

        // Многопрофильная компания
        $org6 = Organization::create([
            'name' => 'Многопрофильная компания "Универсал"',
            'building_id' => $buildings->first()->id,
        ]);

        $org6->phones()->createMany([
            ['phone' => '8-888-888'],
        ]);

        $org6->activities()->attach([
            $activities->where('name', 'Мясная продукция')->first()->id,
            $activities->where('name', 'Молочная продукция')->first()->id,
        ]);
    }
} 