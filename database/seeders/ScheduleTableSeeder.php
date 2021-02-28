<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schedule::create(['start' => '09:00']);
        Schedule::create(['start' => '10:00']);
        Schedule::create(['start' => '11:00']);
        Schedule::create(['start' => '12:00']);
        Schedule::create(['start' => '13:00']);
        Schedule::create(['start' => '14:00']);
        Schedule::create(['start' => '15:00']);
        Schedule::create(['start' => '16:00']);
        Schedule::create(['start' => '17:00']);
        Schedule::create(['start' => '18:00']);
        Schedule::create(['start' => '19:00']);
    }
}
