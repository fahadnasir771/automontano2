<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LateBar extends Model
{
    public $timestamps = false;

    public function operator(){
        return $this->belongsTo(User::class);
    }
}
