<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        // Создаем корневые деятельности
        $food = Activity::create(['name' => 'Еда', 'level' => 1]);
        $cars = Activity::create(['name' => 'Автомобили', 'level' => 1]);
        $services = Activity::create(['name' => 'Услуги', 'level' => 1]);

        // Подкатегории для Еды
        Activity::create([
            'name' => 'Мясная продукция',
            'parent_id' => $food->id,
            'level' => 2
        ]);

        Activity::create([
            'name' => 'Молочная продукция',
            'parent_id' => $food->id,
            'level' => 2
        ]);

        Activity::create([
            'name' => 'Хлебобулочные изделия',
            'parent_id' => $food->id,
            'level' => 2
        ]);

        // Подкатегории для Автомобилей
        $cargoCars = Activity::create([
            'name' => 'Грузовые',
            'parent_id' => $cars->id,
            'level' => 2
        ]);

        Activity::create([
            'name' => 'Легковые',
            'parent_id' => $cars->id,
            'level' => 2
        ]);

        // Подкатегории для Грузовых автомобилей
        Activity::create([
            'name' => 'Запчасти',
            'parent_id' => $cargoCars->id,
            'level' => 3
        ]);

        Activity::create([
            'name' => 'Аксессуары',
            'parent_id' => $cargoCars->id,
            'level' => 3
        ]);

        // Подкатегории для Услуг
        Activity::create([
            'name' => 'Ремонт',
            'parent_id' => $services->id,
            'level' => 2
        ]);

        Activity::create([
            'name' => 'Консультации',
            'parent_id' => $services->id,
            'level' => 2
        ]);
    }
} 