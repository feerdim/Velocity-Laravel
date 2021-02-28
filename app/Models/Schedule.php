<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
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
}
