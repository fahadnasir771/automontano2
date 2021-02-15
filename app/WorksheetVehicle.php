<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorksheetVehicle extends Model
{
    protected $guarded = [];

    public function worksheet()
    {
        return $this->belongsTo(Worksheet::class);
    }

    public function images()
    {
        return $this->hasMany(WorksheetVehicleImage::class);
    }

    public function customer()
    {
        return $this->belongsTo(WorksheetCustomer::class);
    }
}
