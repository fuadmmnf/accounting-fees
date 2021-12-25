<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationFee extends Model
{
    protected $guarded = [];

    public function application(){
        return $this->belongsTo(Application::class);
    }
    public function field(){
        return $this->belongsTo(Field::class);
    }
}
