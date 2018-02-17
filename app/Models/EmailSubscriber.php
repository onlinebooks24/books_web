<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSubscriber extends Model
{
    public function collect_mail_queue()
    {
        return $this->belongsTo('App\Models\CollectMailQueue');
    }
}
