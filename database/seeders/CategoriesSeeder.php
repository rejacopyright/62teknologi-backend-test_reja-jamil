<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\categories;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'atm'],
            ['name' => 'bakery'],
            ['name' => 'bank'],
            ['name' => 'bar'],
            ['name' => 'book_store'],
            ['name' => 'cafe'],
            ['name' => 'clothing_store'],
            ['name' => 'convenience_store'],
            ['name' => 'department_store'],
            ['name' => 'drugstore'],
            ['name' => 'electronics_store'],
            ['name' => 'hospital'],
            ['name' => 'jewelry_store'],
            ['name' => 'movie_theater'],
            ['name' => 'night_club'],
            ['name' => 'park'],
            ['name' => 'pharmacy'],
            ['name' => 'primary_school'],
            ['name' => 'restaurant'],
            ['name' => 'secondary_school'],
            ['name' => 'shoe_store'],
            ['name' => 'shopping_mall'],
            ['name' => 'stadium'],
            ['name' => 'supermarket'],
            ['name' => 'tourist_attraction'],
            ['name' => 'university'],
        ];

        foreach ($data as $item) {
            categories::updateOrCreate($item);
        }
    }
}
