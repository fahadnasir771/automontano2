<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorksheetJob extends Model
{
    public function object()
    {
        return $this->hasOne(WorksheetObject::class, 'id', 'object_id');
    }

    public function timer()
    {
        return $this->hasOne(JobTimer::class);
    }
}
