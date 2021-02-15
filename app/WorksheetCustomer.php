<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorksheetCustomer extends Model
{
    protected $guarded = [];

    public function worksheet()
    {
        return $this->belongsTo(Worksheet::class);
    }

    public function vehicles()
    {
        return $this->hasMany(WorksheetVehicle::class);
    }
}
