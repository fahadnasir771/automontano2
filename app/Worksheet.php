<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Worksheet extends Model
{
    protected $guarded = [];

    public function failures()
    {
        return $this->hasMany(WorksheetFailure::class);
    }

    public function spare_parts()
    {
        return $this->hasMany(WorksheetSparePart::class);
    }

    public function customer()
    {
        return $this->hasOne(WorksheetCustomer::class);
    }

    public function user_customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function vehicle()
    {
        return $this->hasOne(WorksheetVehicle::class);
    }

    public function jobs()
    {
        return $this->hasMany(WorksheetJob::class);
    }
}
