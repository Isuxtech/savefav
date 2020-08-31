<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use SoftDeletes;

    public function category()
    {
        return $this->hasOne(Category::class, 'cat_id', 'category');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        $created = new Carbon($value);
        return $created->toFormattedDateString();
    }


    public function scopeSearch($query,$resultString){
        $query->where(function($query) use ($resultString){
            $query->where('title','like', '%'.$resultString.'%')
                ->orWhere('description','like', '%'.$resultString.'%');
        });
    }
}
