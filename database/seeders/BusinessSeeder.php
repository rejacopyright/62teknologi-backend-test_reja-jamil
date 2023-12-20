<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\business;
use App\Models\location;
use App\Models\categories;
use App\Models\term;
use App\Models\attributes;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $location = location::distinct('id')->pluck('id')->all();
        $location_count = collect($location)->count();


        $category = categories::distinct('id')->pluck('id')->all();
        $category_count = collect($category)->count();

        $term = term::distinct('id')->pluck('id')->all();
        $term_count = collect($term)->count();


        $arr = array_fill(0, 300, '');
        foreach (collect($arr) as $item) {
            $location_rand = $location[rand(0, $location_count - 1)];
            $category_rand = $category[rand(0, $category_count - 1)];
            $term_rand = $term[rand(0, $term_count - 1)];
            $attributes_arr =
                collect(array_fill(0, rand(1, 5), ''))->map(function () {
                    $attributes = attributes::distinct('id')->pluck('id')->all();
                    $attributes_count = collect($attributes)->count();
                    $attributes_rand = $attributes[rand(0, $attributes_count - 1)];
                    return $attributes_rand;
                })->all();
            business::updateOrCreate([
                'name' => fake()->word(),
                'location_id' => $location_rand,
                'category_id' => $category_rand,
                'term_id' => $term_rand,
                'attribute_ids' => $attributes_arr,
                'rating' => rand(0, 5),
                'review_count' => rand(1, 1000),
                'address' => fake()->address(),
                'latitude' => fake()->latitude(),
                'longitude' => fake()->longitude(),
            ]);
        }
    }
}
