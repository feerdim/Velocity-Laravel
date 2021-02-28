<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Game;
use App\Models\Type;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\PlayerSchedule;
use App\Http\Controllers\Controller;

class SoloScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $schedules = Schedule::latest()->with(['player_schedules' => function($query) {
            $query->where('is_solo', '=', true)
                ->where('game_status', '=', 'pending');
        }])->get();
        return view('admin.solo_schedule.index', compact('schedules'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showDetail(PlayerSchedule $solo_schedule, Game $game, Type $type)
    {
        $batches = [];
        $schedules = PlayerSchedule::where('schedule_id', '=', $solo_schedule->schedule_id)
                        ->where('game_id', '=', $game->id)
                        ->where('game_status', '=', 'pending')
                        ->where('is_solo', '=', $solo_schedule->is_solo)
                        ->where('type_id', '=', $type->id)->get();
        // dd(count($batches));
        $counter = 1;
        foreach ($schedules as $schedule) {
            if(count(($batches)) == 0) {
                $batches['batch '. $counter] = [];
                $batches['batch '. $counter][] = $schedule;
            } else {
                if(count($batches['batch '. $counter]) < $type->max_player) {
                    $batches['batch '. $counter][] = $schedule;
                } else {
                    $counter += 1;
                    $batches['batch '. $counter] = [];
                    $batches['batch '. $counter][] = $schedule;
                }
            }
        }
        // dd(count(($batches['batch 1'])));
        // dd(array_keys($batches));
        return view('admin.solo_schedule.show', compact('batches','game', 'type','solo_schedule' ));
    }

}
