<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table='category';
    public function site(){
       return  $this->belongsTo(Site::class,'category','cat_id');
    }
}
