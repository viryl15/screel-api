<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Foundation\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
//use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements AuthenticatableContract
{

    use AuthenticatableTrait, SoftDeletes;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $connection = 'mongodb';

    protected $table = 'users';
    public $timestamps = true;
    protected $softDelete = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider_id',
        'provider_name',
        'token',
        'refresh_token',
        'nickname',
        'username',
        'avatar',
        'expiresIn',
        'latest_screel_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'token',
        'refresh_token',
        'nickname',
        'expiresIn',
        'following_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
//        'profile_photo_url',
//        'name'
            'latest_screel'
    ];

    public function screels(){
        return $this->hasMany(Screel::class, 'user_id');
    }

    public function getLatestScreelAttribute(){
        $screels = $this->screels;
        $this->setHidden(array_merge($this->getHidden(), ['screels']));
        if (count($screels) > 0){
            return $screels[count($screels) - 1];
        }
        return null;//->first();
    }

    public function myLatestScreel() {
        return $this->belongsTo(Screel::class, 'latest_screel_id');
    }

    public function followers(){
        return $this->belongsToMany(User::class, 'user_follows', 'following_id', 'follower_id');
    }

    public function followings(){
        return $this->belongsToMany(User::class, 'user_follows', 'follower_id', 'following_id');
    }


//    public function isDistributor() {
//        return $this->category()->first()->name == 'Distributor';
//    }
//    public function isCustomer() {
//        return $this->category()->first()->name == 'Customer';
//    }

//    Attributes
//    public function getNameAttribute()
//    {
//        return $this->first_name.' '.$this->last_name;
//    }

//    relationships
//    public function category() {
//        return $this->belongsToMany(Category::class, 'user_category', 'user_id', 'category_id');
//    }
//    public function referrer() {
//        return $this->belongsTo(User::class, 'referred_by');
//    }
//    public function getAuthIdentifierName()
//    {
//        // TODO: Implement getAuthIdentifierName() method.
//    }
//
//    public function getAuthIdentifier()
//    {
//        // TODO: Implement getAuthIdentifier() method.
//    }
//
//    public function getAuthPassword()
//    {
//        // TODO: Implement getAuthPassword() method.
//    }
//
//    public function getRememberToken()
//    {
//        // TODO: Implement getRememberToken() method.
//    }
//
//    public function setRememberToken($value)
//    {
//        // TODO: Implement setRememberToken() method.
//    }
//
//    public function getRememberTokenName()
//    {
//        // TODO: Implement getRememberTokenName() method.
//    }
}
