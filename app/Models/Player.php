<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;	
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Player extends Authenticatable implements JWTSubject 
{
    use HasFactory;
    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];
    

    /**
     * topup_charges
     *
     * @return void
     */
    public function topup_charges()
    {
        return $this->hasMany(TopupCharge::class);
    }

    /**
     * activations
     *
     * @return void
     */

    public function activations()
    {
        return $this->hasMany(Activation::class);
    }
    
    public function getAvatarAttribute($avatar)
    {
        if ($avatar != null) :
            if (strpos($avatar, 'googleusercontent') !== false) {
                return asset($avatar);
            } elseif (strpos($avatar, 'ui-avatars.com') !== false) {
                return 'https://ui-avatars.com/api/?name=' . str_replace(' ', '+', $this->name) . '&background=4e73df&color=ffffff&size=100';
            }
            return asset('storage/players/' . $avatar);

        else :
            return 'https://ui-avatars.com/api/?name=' . str_replace(' ', '+', $this->name) . '&background=4e73df&color=ffffff&size=100';
        endif;
    }

    /**
     * player_schedules
     *
     * @return void
     */

    public function player_schedules()
    {
        return $this->hasMany(PlayerSchedule::class);
    }
    /**
     * vs_one_schedule
     *
     * @return void
     */

    public function vs_one_schedules()
    {
        return $this->hasMany(VsOneSchedule::class);
    }

    /**
     * getJWTIdentifier
     *
     * @return void
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
        
    /**
     * getJWTCustomClaims
     *
     * @return void
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
