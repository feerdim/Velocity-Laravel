<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TopupCharge extends Model
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

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * crown_package
     *
     * @return void
     */

    public function crown_package()
    {
        return $this->belongsTo(CrownPackage::class);
    }
}
