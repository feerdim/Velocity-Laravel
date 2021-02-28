<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameMaster extends Model
{
    use HasFactory;
    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = [];

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
     * user
     *
     * @return void
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
