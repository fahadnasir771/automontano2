<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserWorksheetObject extends Model
{
    public function users()
    {
        return $this->belongsToMany(WorksheetObject::class);
    }
}
