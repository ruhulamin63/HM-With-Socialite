<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ManageHotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('hotels')->insert([
                'name' => $faker->company,
                'cost_per_night' => $faker->randomFloat(2, 50, 500),
                'available_rooms' => $faker->numberBetween(1, 10),
                'image' => $faker->imageUrl(640, 480, 'city'),
                'rating' => $faker->randomFloat(1, 1, 5),
                'address' => $faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
