<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
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
     * types
     *
     * @return void
     */

    public function types()
    {
        return $this->belongsToMany(Type::class, 'team_types');
    }
}
