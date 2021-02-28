<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrownPackage extends Model
{
    use HasFactory;

    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * topup_charges
     *
     * @return void
     */

    public function topup_charges()
    {
        return $this->hasMany(TopupCharge::class);
    }
}
