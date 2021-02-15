<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorksheetVehicleImage extends Model
{
    protected $guarded = [];

    public function vehicle()
    {
        return $this->belongsTo(WorksheetVehicle::class);
    }
}
