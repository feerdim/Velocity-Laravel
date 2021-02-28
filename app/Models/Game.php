<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * getImageAttribute
     *
     * @param  mixed $image
     * @return void
     */
    public function getImageAttribute($image)
    {
        return asset('storage/games/' . $image);
    }

     /**
     * getBackgroundAttribute
     *
     * @param  mixed $image
     * @return void
     */
    public function getBackgroundAttribute($image)
    {
        return asset('storage/gameBackgrounds/' . $image);
    }

    /**
     * ativations
     *
     * @return void
     */

    public function ativations()
    {
        return $this->hasMany(Activation::class);
    }

    /**
     * solo
     *
     * @return void
     */

    public function solo()
    {
        return $this->hasOne(Solo::class);
    }

    /**
     * team
     *
     * @return void
     */

    public function team()
    {
        return $this->hasOne(Team::class);
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
}
