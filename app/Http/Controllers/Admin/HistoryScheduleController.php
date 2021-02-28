<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\PlayerSchedule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\Game;
use App\Models\Player;
use App\Models\Type;
use App\Models\VsOneSchedule;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class HistoryScheduleController extends Controller
{
    /**
     * Khusus super admin
     *
     * @return void
     */
    public function allHistory()
    {
        $histories = PlayerSchedule::get();
        $vsOnes = VsOneSchedule::where('game_status', 'finish')->get();
        // dd($vsOne);
        return view('admin.history_schedule.all', compact('histories', 'vsOnes'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $histories = PlayerSchedule::latest()->whereHas('game_master', function($q) {
            $q->whereHas('user', function($q) {
                $q->where('id', auth()->user()->id);
            });
        })->get();
        
        return view('admin.history_schedule.index', compact('histories'));
    }

    public function allPending()
    {
        $pendings = PlayerSchedule::latest()->where('game_status','pending')->get();
        
        return view('admin.history_schedule.pending', compact('pendings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PlayerSchedule $player_schedule, Game $game, Type $type)
    {
        $awards = Award::where('type_id', $type->id)->orderBy('nominal','desc')->get();
        $schedules = PlayerSchedule::where('schedule_id', '=', $player_schedule->schedule_id)
                        ->where('game_id', '=', $game->id)
                        ->where('game_master_id', '=', $player_schedule->game_master_id)
                        ->where('game_status', '=', $player_schedule->game_status)
                        ->where('type_id', '=', $type->id)->get();
        return view('admin.history_schedule.show', compact('schedules','game', 'type','player_schedule','awards' ));
    }

     /**
     * Assign winner
     *
     * 
     * @return \Illuminate\Http\Response
     */
    
    public function winner(PlayerSchedule $player_schedule, Request $request)
    {
        // array_shift($request->all());
        $all_request = $request->except('_token');
        // $counter = 50;
        
        foreach ($all_request as $key => $request) {
            $player = Player::where('email', $all_request[$key])->first();
            if(!$player) {
                return redirect()->route('admin.history_schedule.show', [
                    'game' => $player_schedule->game_id,
                    'player_schedule' => $player_schedule->id,
                    'type' => $player_schedule->type
                ])->with(['error' => 'Email ' . $all_request[$key] . ' not found !']);
            } 
        }

        // -----
        // winner
        foreach ($all_request as $key => $request) {
            $player = Player::where('email', $all_request[$key])->first();
            // $counter -= 10;
            $player->crown += (int) $key;
            // $player->score += $counter;
            $player->save();
            // tambah game_score di masing2 player scheduel
            // $updated_player = PlayerSchedule::where('schedule_id','=',$player_schedule->schedule_id)
            //     ->where('game_id','=',$player_schedule->game_id)
            //     ->where('type_id','=',$player_schedule->type_id)
            //     ->where('is_solo','=',$player_schedule->is_solo)
            //     ->where('player_id','=',$player->id)->first();
            // $updated_player->game_score = $counter + ($updated_player->win_count - $updated_player->lose_count);
            // $updated_player->save();
        }
        //looping foreach
        
        // update score di player_schedule
        PlayerSchedule::where('schedule_id','=',$player_schedule->schedule_id)
                                ->where('game_id','=',$player_schedule->game_id)
                                ->where('game_master_id','=',$player_schedule->game_master_id)
                                ->where('type_id','=',$player_schedule->type_id)
                                ->where('is_solo','=',$player_schedule->is_solo)
                                ->update(['game_status' => 'finish']);
        $player_schedule->save();
        $this->broadcastMessage('Game has ended','Check your game in the profile',$player_schedule);
        return redirect()->route('admin.history_schedule.show', [
            'game' => $player_schedule->game_id,
            'player_schedule' => $player_schedule->id,
            'type' => $player_schedule->type
        ])->with(['success' => 'Success Assgin Winner']);
    }

    private function broadcastMessage($title, $message, $player_schedule)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($message)
                            ->setSound('default')
                            ->setClickAction(env('APP_URL').'/admin/player');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'message' => $message
        ]);
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        
        $tokens = Player::with(['player_schedules' => function($query) {
            global $player_schedule;
            $query->whereDate('created_at', '=', date('Y-m-d'))
            ->where('schedule_id','=',$player_schedule->schedule_id)
            ->where('game_id','=',$player_schedule->game_id)
            ->where('type_id','=',$player_schedule->type_id)
            ->where('game_master_id','=',$player_schedule->game_master_id)
            ->where('is_solo','=',$player_schedule->is_solo);
        }])->pluck('fcm_token')->toArray();
        
        
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        
        return $downstreamResponse->numberSuccess();
    }

    /**
     * Update player win count
     *
     * 
     * @return \Illuminate\Http\Response
     */

    public function setWinCount(PlayerSchedule $player_schedule, Player $player ,Request $request)
    {
        // tambahkan win_total di player
        $player->win_total += $request->win_count;
        $player->score += $request->win_count;
        $player->save();
        // set win_count di player schedule
        $schedule = PlayerSchedule::where('schedule_id','=',$player_schedule->schedule_id)
                                ->where('game_id','=',$player_schedule->game_id)
                                ->where('type_id','=',$player_schedule->type_id)
                                ->where('is_solo','=',$player_schedule->is_solo)
                                ->where('game_master_id','=',$player_schedule->game_master_id)
                                ->where('game_status','=',$player_schedule->game_status)
                                ->where('player_id','=',$player->id)->first();
        $schedule->win_count = $request->win_count;
        $schedule->game_score += $request->win_count;
        $schedule->save();
        return redirect()->route('admin.history_schedule.show', [
            'game' => $player_schedule->game_id,
            'player_schedule' => $player_schedule->id,
            'type' => $player_schedule->type
        ]);
    }

    /**
     * Update player lose count
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function setLoseCount(PlayerSchedule $player_schedule, Player $player ,Request $request)
    {
        // tambahkan lose_total di player
        $player->lose_total += $request->lose_count;
        if($player->score - $request->lose_count >= 0) {
            $player->score -= $request->lose_count;
        } else {
            $player->score = 0;
        }
        $player->save();
        // set lose_count di player schedule
        $schedule = PlayerSchedule::where('schedule_id','=',$player_schedule->schedule_id)
                                ->where('game_id','=',$player_schedule->game_id)
                                ->where('type_id','=',$player_schedule->type_id)
                                ->where('is_solo','=',$player_schedule->is_solo)
                                ->where('game_master_id','=',$player_schedule->game_master_id)
                                ->where('game_status','=',$player_schedule->game_status)
                                ->where('player_id','=',$player->id)->first();
        $schedule->lose_count = $request->lose_count;
        if($schedule->game_score - $request->lose_count >= 0) {
            $schedule->game_score -= $request->lose_count;
        } else {
            $schedule->game_score = 0;
        }
        $schedule->save();
        return redirect()->route('admin.history_schedule.show', [
            'game' => $player_schedule->game_id,
            'player_schedule' => $player_schedule->id,
            'type' => $player_schedule->type
        ]);
    }

    /**
     * refund
     *
     * 
     * @return \Illuminate\Http\Response
     */

    public function refund(PlayerSchedule $player_schedule)
    {
        // ubah status semua player schedule jadi canceled
        // balikin semua crown player
        PlayerSchedule::where('schedule_id','=',$player_schedule->schedule_id)
                ->where('game_id','=',$player_schedule->game_id)
                ->where('type_id','=',$player_schedule->type_id)
                ->where('is_solo','=',$player_schedule->is_solo)
                ->where('game_master_id','=',$player_schedule->game_master_id)
                ->update(['game_status' => 'canceled']);
        $all_players = PlayerSchedule::where('schedule_id','=',$player_schedule->schedule_id)
            ->where('game_id','=',$player_schedule->game_id)
            ->where('type_id','=',$player_schedule->type_id)
            ->where('game_status','=','canceled')
            ->where('game_master_id','=',$player_schedule->game_master_id)
            ->where('is_solo','=',$player_schedule->is_solo)->get();
        if($all_players) {
            foreach ($all_players as $schedule) {
                $schedule->player->crown += $player_schedule->type->crown_price;
                $schedule->player->save();
            }
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        } 
    }
}
