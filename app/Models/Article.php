<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    
    public function category()
    {
    	return $this->belongsTo('App\Models\Category');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function uploads()
    {
        return $this->hasMany('App\Models\Upload');
    }

    public function product_orders()
    {
        return $this->hasMany('App\Models\ProductOrder');
    }

    public function site_costs()
    {
        return $this->hasMany('App\Models\SiteCost');
    }

    public function thumbnail_image()
    {
        return $this->belongsTo('App\Models\Upload', 'thumbnail_id');
    }
}
