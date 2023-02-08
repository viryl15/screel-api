<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $table = 'reactions';

    protected $fillable = [
        'label',
        'external_link',
        'link',
    ];
}
