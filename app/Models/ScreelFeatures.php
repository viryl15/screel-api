<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class ScreelFeatures extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';

    protected $table = 'screel_features';
    protected $fillable = [
        'title',
        'content',
        'schedule',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'schedule' => 'datetime',
    ];

}
