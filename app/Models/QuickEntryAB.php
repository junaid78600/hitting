<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickEntryAB extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId','pitchId','abNumber', 'primaryPitch', 'secondaryPitch', 'result','whyWasThisSpot', 'image','date','time'
    ];
    
   
    public function getImageAttribute($value='image')
    {
        if(!$value){
            return null;
        }
        return env('APP_URL').'images/'.$value;
    }
    
    


}
