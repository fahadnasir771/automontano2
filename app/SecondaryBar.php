<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecondaryBar extends Model
{
  // public $timestamps = false;

  public function main_bar(){
      return $this->belongsTo(MainBar::class);
  }
}
