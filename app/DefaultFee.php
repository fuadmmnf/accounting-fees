<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultFee extends Model
{
    protected $guarded = [];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function field(){
        return $this->belongsTo(Field::class);
    }
}
