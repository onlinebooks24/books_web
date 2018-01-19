<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteCost extends Model
{
    public function article()
    {
        return $this->belongsTo('App\Models\Article');
    }

    public function site_cost_type()
    {
        return $this->belongsTo('App\Models\SiteCostType');
    }

}
