<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'body', 'image','category_id','user_id','status',
    ];
       public function category()
    {
        return $this->belongsTo('App\Category');//belongsto ka table twy ko chate payy
    }
     public function user()
    {
        return $this->belongsTo('App\User');//belongsto ka table twy ko chate payy
    }
    public function reviews()
    {
        return $this->hasMany('App\Review');//belongsto ka table twy ko chate payy
    }
}
