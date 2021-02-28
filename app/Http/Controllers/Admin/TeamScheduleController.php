<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Game;
use App\Models\Type;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\PlayerSchedule;
use App\Http\Controllers\Controller;

class TeamScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = Schedule::latest()->with(['player_schedules' => function($query) {
            $query->where('is_solo', '=', false)
            ->where('game_status', '=', 'pending')
            ->whereDate('created_at', '=', Carbon::now());
        }])->get();
        return view('admin.team_schedule.index', compact('schedules'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showDetail(PlayerSchedule $team_schedule, Game $game, Type $type)
    {
        $batches = [];
        $schedules = PlayerSchedule::where('schedule_id', '=', $team_schedule->schedule_id)
                        ->where('game_id', '=', $game->id)
                        ->where('game_status', '=', 'pending')
                        ->where('is_solo', '=', $team_schedule->is_solo)
                        ->where('type_id', '=', $type->id)->get();
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
        return view('admin.team_schedule.show', compact('batches','game', 'type','team_schedule' ));
    }

}
