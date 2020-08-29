<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use SoftDeletes;

    public function category(){
       return $this->hasOne(Category::class, 'cat_id','category');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id','id');
    }
}
