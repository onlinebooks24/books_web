<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideosTemplate extends Model
{
    protected $appends = ['background_image_location', 'audio_location'];

    public function getBackgroundImageLocationAttribute(){
        $background_image_location = public_path('uploads/videos/templates/'. $this->template_name . $this->background_image);
        return $background_image_location;
    }

    public function getAudioLocationAttribute(){
        $audio_location = public_path('uploads/videos/templates/'. $this->template_name . $this->audio_name);
        return $audio_location;
    }
}
