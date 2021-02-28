<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class VsOneTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::create([
            'name' => '1 VS 1',
            'min_player' => 2,
            'max_player' => 2,
            'crown_price' => 100,
        ]);
    }
}
