<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    public function article(){
        return $this->belongsTo('App\Models\Article');
    }
}
