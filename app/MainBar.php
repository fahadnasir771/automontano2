<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainBar extends Model
{
    // public $timestamps = false;

    public function secondary_bars(){
        return $this->hasMany(SecondaryBar::class);
    }
}
