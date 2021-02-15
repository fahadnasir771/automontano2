<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorksheetObject extends Model
{
    //
    protected $fillable = [
      'title',
      'min_time',
      'max_time',
    ];

    protected $hidden = [
      'created_at',
      'updated_at',
    ];
    public function operators(){
      return $this->belongsToMany(User::class);
    }

    protected $guarded = [];

    public function worksheet()
    {
      return $this->belongsTo(Worksheet::class);
    }

    public function job_timers()
    {
      return $this->hasMany(JobTimer::class);
    }
}
