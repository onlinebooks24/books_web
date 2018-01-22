<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchedulerJob extends Model
{
    public function article()
    {
        return $this->belongsTo('App\Models\Article');
    }

    public function notification_type()
    {
        return $this->belongsTo('App\Models\NotificationType');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
