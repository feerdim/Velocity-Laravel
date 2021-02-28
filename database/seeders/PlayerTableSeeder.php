<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Seeder;

class PlayerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Player::create([
            'name'      => 'Fauzi Fadillah',
            'email'     => 'fauzi@gmail.com',
            'password'  => bcrypt('password')
        ]);
        Player::create([
            'name'      => 'Syakir Yaqhdi',
            'email'     => 'syakir@gmail.com',
            'password'  => bcrypt('password')
        ]);
    }
}
