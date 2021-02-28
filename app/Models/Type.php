<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    
    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * solos
     *
     * @return void
     */

    public function solos()
    {
        return $this->belongsToMany(Solo::class, 'solo_types');
    }

    /**
     * teams
     *
     * @return void
     */

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_types');
    }

    /**
     * awards
     *
     * @return void
     */

    public function awards()
    {
        return $this->hasMany(Award::class);
    }
    /**
     * vs_one_schedule
     *
     * @return void
     */

    public function player_schedules()
    {
        return $this->hasMany(PlayerSchedule::class);
    }

    public function vs_one_schedules()
    {
        return $this->hasMany(VsOneSchedule::class);
    }
}
