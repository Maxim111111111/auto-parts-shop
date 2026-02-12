<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Part;
use Illuminate\Database\Seeder;

class PartSeeder extends Seeder
{
    public function run(): void
    {
        $parts = [
            [
                'sku' => 'BRK-PAD-001',
                'name' => 'Колодки тормозные передние',
                'brand' => 'Bosch',
                'category' => 'Тормозная система',
                'price' => 2490,
                'stock' => 14,
                'is_active' => true,
            ],
            [
                'sku' => 'FLT-OIL-010',
                'name' => 'Фильтр масляный',
                'brand' => 'MANN',
                'category' => 'Фильтры',
                'price' => 690,
                'stock' => 35,
                'is_active' => true,
            ],
            [
                'sku' => 'ENG-BLT-020',
                'name' => 'Ремень ГРМ',
                'brand' => 'Gates',
                'category' => 'Двигатель',
                'price' => 3190,
                'stock' => 8,
                'is_active' => true,
            ],
            [
                'sku' => 'SUS-SHK-030',
                'name' => 'Амортизатор задний',
                'brand' => 'KYB',
                'category' => 'Подвеска',
                'price' => 4590,
                'stock' => 4,
                'is_active' => true,
            ],
            [
                'sku' => 'ELC-PLG-040',
                'name' => 'Свеча зажигания',
                'brand' => 'NGK',
                'category' => 'Электрика',
                'price' => 520,
                'stock' => 0,
                'is_active' => false,
            ],
        ];

        foreach ($parts as $item) {
            $categoryId = Category::query()->where('title', $item['category'])->value('id');

            Part::query()->updateOrCreate(
                ['sku' => $item['sku']],
                [
                    'name' => $item['name'],
                    'brand' => $item['brand'],
                    'category_id' => $categoryId,
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'is_active' => $item['is_active'],
                ],
            );
        }
    }
}
