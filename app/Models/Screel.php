<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Screel extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $table = 'screels';

    protected $fillable = [
        'user_id',
        'content',
    ];

    protected $hidden = [
        'user_id'
    ];
}
