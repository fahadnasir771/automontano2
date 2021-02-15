<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorksheetSparePart extends Model
{
    protected $guarded = [];

    public function worksheet()
    {
        return $this->belongsTo(Worksheet::class);
    }
}
