<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VsOneSchedule extends Model
{
    use HasFactory;
    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * game
     *
     * @return void
     */

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * type
     *
     * @return void
     */

    public function type()
    {
        return $this->belongsTo(Type::class);
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
     * game_master
     *
     * @return void
     */

    public function game_master()
    {
        return $this->belongsTo(GameMaster::class);
    }
}
