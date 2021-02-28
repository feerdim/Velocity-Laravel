<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activation extends Model
{
    use HasFactory;
    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * getCreatedAtAttribute
     *
     * @param  mixed $date
     * @return void
     */
    public function getCreatedAtAttribute($date)
    {   
        return Carbon::parse($date)->format('d-M-Y');
    }

    /**
     * player
     *
     * @return void
     */

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * game
     *
     * @return void
     */

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
