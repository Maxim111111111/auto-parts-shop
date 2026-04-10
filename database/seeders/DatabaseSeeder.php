<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        if (app()->environment('production') && ! filter_var((string) env('SEED_DEMO_DATA', 'false'), FILTER_VALIDATE_BOOL)) {
            return;
        }

        $this->call([
            CategorySeeder::class,
            PartSeeder::class,
        ]);

        User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password'), 'is_admin' => true],
        );
    }
}
