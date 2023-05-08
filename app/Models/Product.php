<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'categoryId', 'title', 'price', 'description', 'date', 'time', 'status','image'
    ];

    public function getImageAttribute($value='image')
    {
        if(!$value){
            return null;
        }
        return env('APP_URL').'product/'.$value;
    }

    public function category(){

        return $this->belongsTo(Category::class,'categoryId');

    }

}
