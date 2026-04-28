<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Farm;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            AdminSeeder::class,
        ]);

        Farm::create([
            'name' => 'مزرعة تجريبية',
            'location' => 'الموقع التجريبي',
            'capacity' => 1000,
        ]);
    }
}
