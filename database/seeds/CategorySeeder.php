<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = ['সাফ কবলা', 'হেবার ঘোষণাপত্র', 'দানের ঘোষণাপত্র', 'দানপত্র', 'না-দাবী/ মুক্তিপত্র', 'পাওয়ার অব অ্যাটর্নী বাতিলকরন', 'ঘোষণাপত্র দলিল', 'অপ্রত্যাহারযোগ্য পাওয়ার অব অ্যাটর্নী (পণমূল্য ব্যতীত)', 'সাধারণ পাওয়ার অব অ্যাটর্নী', 'বায়নাপত্র দলিল', 'বন্টননামা দলিল', 'অন্যান্য'];
        foreach ($categories as $category){
            \App\Category::create(['name' => $category]);
        }
    }
}
