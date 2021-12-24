<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = ['সাফ কবলা', 'হেবার ঘোষণাপত্র', 'দানের ঘোষণাপত্র', 'দানপত্র', 'অন্যান্য'];
        foreach ($categories as $category){
            \App\Category::create(['name' => $category]);
        }
    }
}
