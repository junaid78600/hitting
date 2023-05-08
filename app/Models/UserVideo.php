<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVideo extends Model
{
    use HasFactory;

    protected $fillable = [

        'userId','image', 'title', 'date','time'

    ];

    public function getImageAttribute($value='image')
    {
        if(!$value){
            return null;
        }
        return env('APP_URL').'userVideo/'.$value;
    }

}
