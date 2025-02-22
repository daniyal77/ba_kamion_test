<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CarsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $brandIds = DB::table('brands')->pluck('id')->toArray();

        $gearboxTypes = ['manual', 'automatic'];
        $bodyTypes = ['pickup', 'suv', 'hatchback'];
        $fuelTypes = ['gasoline', 'diesel', 'electric', 'hybrid'];
        $statuses = ['available', 'sold', 'unavailable'];

        // ایجاد 10 رکورد نمونه برای جدول cars
        for ($i = 0; $i < 10; $i++) {
            $isNew = $faker->boolean(30); // احتمال 30% برای صفر کیلومتر بودن

            DB::table('cars')->insert([
                'name'         => ucfirst($faker->word) . ' ' . ucfirst($faker->word),
                'model'        => $faker->bothify('Model-###'),
                'year'         => $faker->numberBetween(2000, date('Y')),
                'price'        => $faker->numberBetween(10000, 100000),
                'mileage'      => $isNew ? null : $faker->numberBetween(5000, 200000),
                'color'        => $faker->safeColorName,
                'description'  => $faker->optional()->paragraph,
                'image_urls'   => json_encode([
                    $faker->imageUrl(640, 480, 'cars', true),
                    $faker->imageUrl(640, 480, 'cars', true),
                    $faker->imageUrl(640, 480, 'cars', true)
                ]),
                'is_new'       => $isNew,
                'gearbox_type' => $faker->randomElement($gearboxTypes),
                'body_type'    => $faker->randomElement($bodyTypes),
                'fuel_type'    => $faker->randomElement($fuelTypes),
                'status'       => $faker->randomElement($statuses),
                'brand_id'     => $faker->randomElement($brandIds),
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ]);
        }
    }
}
