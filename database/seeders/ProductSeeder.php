<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {

            $clothingCategory = Category::where('name', 'Ropa')->first();
            $homeCategory = Category::where('name', 'Hogar')->first();
            
            if(isset($clothingCategory)){
                Product::create([
                    'name' => 'Camiseta',
                    'price' => 20,
                    'category_id' => $clothingCategory->id,
                ]);
            }

            if(isset($homeCategory)){
                Product::create([
                    'name' => 'Mesa',
                    'price' => 100,
                    'category_id' => $homeCategory->id,
                ]);
            }

        } catch (\Exception $e) {
            echo "Error al ejecutar el seeder de productos: " . $e->getMessage();
        }
    }
}
