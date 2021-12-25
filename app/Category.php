<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function defaultfees(){
        return $this->hasMany(DefaultFee::class);
    }


}
