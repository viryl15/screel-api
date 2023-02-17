<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class ScreelReaction extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $table = 'screel_reactions';

    protected $fillable = [
        'screel_id',
        'reaction_id',
        'count',
    ];

    protected $with = ['reaction'];


    public function screelers(){
        return $this->belongsToMany(User::class, null, 'screel_reaction_ids', 'screeler_ids');
    }


    public function screel(){
        return $this->belongsTo(Screel::class);
    }

    public function reaction(){
        return $this->belongsTo(Reaction::class, 'reaction_id');
    }
}
