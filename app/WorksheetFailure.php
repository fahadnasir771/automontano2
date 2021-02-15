<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorksheetFailure extends Model
{
    protected $guarded = [];

    public function worksheet()
    {
        return $this->belongsTo(Worksheet::class);
    }

}
