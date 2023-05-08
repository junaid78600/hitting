<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'userID', 'description', 'commitment', 'tension','forwards', 'pattern', 'spacing', 'recoil', 'vibes','head_height', 'date','time'
    ];

}
