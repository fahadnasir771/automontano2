<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobTimer extends Model
{
    protected $guarded = [];
    
    public function job()
    {
        return $this->belongsTo(WorksheetJob::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function spare_parts()
    {
        return $this->hasMany(JobTimerSparePart::class);
    }

    
}
