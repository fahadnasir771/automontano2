<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobTimerSparePart extends Model
{
    public function job_timer()
    {
        return $this->belongsTo(JobTimer::class);
    }
}
