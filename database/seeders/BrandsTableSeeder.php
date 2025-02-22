<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BrandsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // ایجاد 10 رکورد نمونه برای جدول brands
        for ($i = 0; $i < 10; $i++) {
            DB::table('brands')->insert([
                'name' => $faker->company,
                'logo_url' => $faker->optional()->imageUrl(200, 200, 'business', true, 'Logo'),
                'description' => $faker->optional()->paragraph,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
