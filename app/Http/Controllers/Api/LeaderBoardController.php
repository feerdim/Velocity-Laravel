<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PlayerSchedule;
use App\Models\VsOneSchedule;
use App\Models\GameMaster;
use App\Models\Type;

class LeaderBoardController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    } 
    public function show($id, $slug)
    {
        $room = GameMaster::where('room_id', $slug)->first();
        $type = Type::find($id);
        if($type->name == '1 VS 1'){
            $leader_boards = VsOneSchedule::where('game_master_id', $room->id)->with(['player'])->get();
            $leader = $leader_boards;
            foreach($leader_boards as $i=>$leader_board){
                $index = $leader_board->player->email;
                if($leader_board->winner == $index){
                    $leader[0] = $leader_board;
                }
                else{
                    $leader[1] = $leader_board;
                }
            }
            return response()->json([
                'success' => true,
                'leader_boards'    => $leader,
                'game' => $leader_boards->first()->game,
                'type' => $type,
                'time' => '',
                'schedule' => '',
            ], 200);
        }
        else{
            $player_schedule = PlayerSchedule::find($id);
            $leader_boards = PlayerSchedule::where('schedule_id','=',$player_schedule->schedule_id)
            ->where('game_id','=',$player_schedule->game_id)
            ->where('type_id','=',$player_schedule->type_id)
            ->where('is_solo','=',$player_schedule->is_solo)
            ->where('game_status','=',$player_schedule->game_status)
            ->where('game_master_id','=',$player_schedule->game_master_id)
            ->with(['player'])
            ->orderBy('game_score','desc')
            ->get();
            return response()->json([
                'success' => true,
                'leader_boards'    => $leader_boards,
                'game' => $player_schedule->game,
                'type' => $player_schedule->type,
                'time' => $player_schedule->schedule,
                'schedule' => $player_schedule,
            ], 200);
        }

        return response()->json([
            'success' => false,
        ], 500);
        
    }
}
