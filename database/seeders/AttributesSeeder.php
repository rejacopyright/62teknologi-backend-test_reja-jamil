<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\attributes;

class AttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'hot_and_new', 'title' => 'Hot And New'],
            ['name' => 'request_a_quote', 'title' => 'Request A Quote'],
            ['name' => 'reservation', 'title' => 'Reservation'],
            ['name' => 'waitlist_reservation', 'title' => 'Waitlist Reservation'],
            ['name' => 'deals', 'title' => 'Deals'],
            ['name' => 'gender_neutral_restrooms', 'title' => 'Gender Neutral Restrooms'],
            ['name' => 'open_to_all', 'title' => 'Open To All'],
            ['name' => 'wheelchair_accessible', 'title' => 'Wheelchair Accessible'],
            ['name' => 'liked_by_vegetarians', 'title' => 'Liked By Vegetarians'],
            ['name' => 'outdoor_seating', 'title' => 'Outdoor Seating'],
            ['name' => 'parking_garage', 'title' => 'Parking Garage'],
            ['name' => 'parking_lot', 'title' => 'Parking Lot'],
            ['name' => 'parking_street', 'title' => 'Parking Street'],
            ['name' => 'parking_valet', 'title' => 'Parking Valet'],
            ['name' => 'parking_validated', 'title' => 'Parking Validated'],
            ['name' => 'parking_bike', 'title' => 'Parking Bike'],
            ['name' => 'restaurants_delivery', 'title' => 'Restaurants Delivery'],
            ['name' => 'restaurants_takeout', 'title' => 'Restaurants Takeout'],
            ['name' => 'wifi_free', 'title' => 'Wifi Free'],
            ['name' => 'wifi_paid', 'title' => 'Wifi Paid'],
        ];

        foreach ($data as $item) {
            $updater = collect($item)->only(['name'])->toArray();
            $creator = collect($item)->except(['name'])->toArray();
            attributes::updateOrCreate($updater, $creator);
        }
    }
}
