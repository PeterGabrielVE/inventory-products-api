<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            Category::create(['name' => 'Hogar']);
            Category::create(['name' => 'Alimentos']);
            Category::create(['name' => 'Deportes']);
            Category::create(['name' => 'Ropa']);
            Category::create(['name' => 'Maquillaje']);
        } catch (\Exception $e) {
            echo "Error al ejecutar el seeder de categorías: " . $e->getMessage();
        }
    }
}
