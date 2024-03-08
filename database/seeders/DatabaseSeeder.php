<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Model::unguard();
        $this->call(CategorySeeder::class); 
        $this->call(ProductSeeder::class); 
        $this->call(UsersTableSeeder::class);
        
        Model::reguard();
    }
}
