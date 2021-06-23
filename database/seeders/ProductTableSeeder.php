<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $avail_stocks = 50;
        $products = array('ice cream', 'pizza', 'pasta', 'ramen', 'burger', 'roast chicken');

        foreach($products as $product){
            $avail_stocks++;
            Product::create([
                'name' => $product,
                'avalable_stocks' => $avail_stocks
            ]);

        }
    }
}
