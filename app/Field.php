<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $guarded = [];

    public function defaultfees(){
        return $this->hasMany(DefaultFee::class);
    }
}
