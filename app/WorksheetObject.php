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
      return $this->belongsToMany('App\User','user_worksheet_objects','worksheet_object_id','user_id');
    }
    public function UserWorksheetObjects(){
      return $this->belongsToMany('App\UserWorksheetObject','user_worksheet_objects','worksheet_object_id' ,'user_id');
    }
}
