<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Screel extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mongodb';

    protected $table = 'screels';

    public $timestamps = true;
    protected $softDelete = true;

    protected $fillable = [
        'user_id',
        'content',
    ];

//    public $appends = [];

    protected $hidden = [
        'user_id',
        'tag_id'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['tags', 'screelReactions'];

//    public function getTagAttribute($value)
//    {
//        return $this->tags; // return screel tags
//    }

    public function tags(){
        return $this->belongsToMany(Tag::class, 'screel_tags', 'screel_id', 'tag_id');
    }

    public function owner(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function screelReactions(){
        return $this->hasMany(ScreelReaction::class, 'screel_id', '_id');
    }
}
