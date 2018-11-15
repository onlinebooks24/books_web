<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $appends = ['video_link'];

    public function getVideoLinkAttribute(){
        $video_file = "/uploads/videos/final_videos/". $this->video_name;
        return $video_file;
    }

    public function getBackgroundImageLocationAttribute(){
        $background_image_location = public_path('uploads/videos/templates/'. $this->template_name . '/' . $this->background_image);
        return $background_image_location;
    }

    public function video_template(){
        return $this->belongsTo('App\Models\VideosTemplate');
    }

    public function article(){
        return $this->belongsTo('App\Models\Article');
    }
}
