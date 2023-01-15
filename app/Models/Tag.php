<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'created_by'
    ];

    protected $hidden = [
        'screel_id',
        'created_by'
    ];

    protected $appends = [
        'screel_count'
    ];


    public function getScreelCountAttribute()
    {
        return $this->screels()->count();
    }

    public function screels(){
        return $this->belongsToMany(Screel::class, 'screel_tags', 'tag_id', 'screel_id');
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
