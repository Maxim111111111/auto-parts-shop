<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Тормозная система',
            'Подвеска',
            'Двигатель',
            'Фильтры',
            'Электрика',
        ];

        foreach ($categories as $title) {
            Category::query()->firstOrCreate(['title' => $title]);
        }
    }
}
