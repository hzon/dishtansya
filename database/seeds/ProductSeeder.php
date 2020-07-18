<?php

use App\Product;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i=0; $i<30; $i++) {
            $product = new Product;
            $product->name = $faker->word;
            $product->available_stock = $faker->numberBetween(1, 999);
            $product->save();
        }
    }
}
