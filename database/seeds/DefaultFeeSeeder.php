<?php

use Illuminate\Database\Seeder;

class DefaultFeeSeeder extends Seeder {
    public function run() {
        $categories = \App\Category::all(); // ['সাফ কবলা', 'হেবার ঘোষণাপত্র', 'দানের ঘোষণাপত্র', 'দানপত্র', 'না-দাবী/ মুক্তিপত্র', 'পাওয়ার অব অ্যাটর্নী বাতিলকরন', 'ঘোষণাপত্র দলিল', 'অপ্রত্যাহারযোগ্য পাওয়ার অব অ্যাটর্নী (পণমূল্য ব্যতীত)', 'সাধারণ পাওয়ার অব অ্যাটর্নী', 'বায়নাপত্র দলিল', 'বন্টননামা দলিল', 'অন্যান্য'];
        $fields = \App\Field::all(); // ['রেজিস্ট্রেশন ফি', 'ই ফিস', 'এন ফিস', 'স্ট্যাম্প শুল্ক', 'স্থানীয় সরকার কর', 'উৎসে আয়কর (53H)', 'এন এন ফিস', '53FF', '53HH', 'ভ্যাট'];
        $defaults = [
            ['p_1', 't_100', 't_160', 'p_1.5', 'p_3', 'p_1', 't_240', '', '', ''],
            ['t_100', 't_100', 't_160', 't_200', '', '', 't_240', '', '', ''],
            ['t_100', 't_100', 't_160', 't_200', '', '', 't_240', '', '', ''],
            ['p_1', 't_100', 't_160', 'p_1.5', 'p_3', '', 't_240', '', '', ''],
        ];

        for ($c= 0; $c < count($categories) -1 ; $c++)  { // exclude অন্যান্য
            for ($i = 0; $i < count($fields); $i++) { // excluding 53ff  +  53hh + vat
                if ($defaults[$c][$i] == '') continue;

                $val = explode('_', $defaults[$c][$i]);
                \App\DefaultFee::create([
                    'category_id' => $categories[$c]->id,
                    'field_id' => $fields[$i]->id,
                    'unit' => ($val[0] == 'p')? 0: 1,
                    'amount' => floatval($val[1])
                ]);

            }
        }
    }
}
