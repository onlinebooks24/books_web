<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    public function video_templates(){
        return $this->belongsTo('App\Models\VideosTemplate');
    }
}
