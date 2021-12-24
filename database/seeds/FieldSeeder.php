<?php

use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    public function run()
    {
        $fields = ['রেজিস্ট্রেশন ফি', 'ই ফিস', 'এন ফিস', 'স্ট্যাম্প শুল্ক', 'স্থানীয় সরকার কর', 'উৎসে আয়কর (53H)', 'এন এন ফিস'];
        foreach ($fields as $field){
            \App\Field::create(['name' => $field]);
        }
    }
}
