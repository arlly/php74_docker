<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 100; $i++) {
            \App\Eloquent\Product::create([
                'name' => '商品' . $i,
                'product_code' => 'product_' . $i . '_abcde',
                'price' => 100 * $i,
            ]);
        }
    }
}
